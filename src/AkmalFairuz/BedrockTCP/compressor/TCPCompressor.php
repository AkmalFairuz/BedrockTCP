<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\compressor;

use pocketmine\network\mcpe\compression\Compressor;
use pocketmine\network\mcpe\compression\DecompressionException;
use pocketmine\network\mcpe\compression\ZlibCompressor;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Utils;
use function function_exists;
use function ord;
use function strlen;
use function substr;
use function zlib_decode;
use function zlib_encode;
use const ZLIB_ENCODING_RAW;

class TCPCompressor implements Compressor{

    use SingletonTrait;

    const ZLIB = 1;
    const ZSTD = 2;

    public static function make() : self{
        return new self(5, ZlibCompressor::DEFAULT_THRESHOLD, ZlibCompressor::DEFAULT_MAX_DECOMPRESSION_SIZE);
    }

    public function __construct(
        private int $level,
        private int $threshold,
        private int $maxDecompressionSize){
    }

    public function willCompress(string $data): bool{
        return $this->threshold > -1 && strlen($data) >= $this->threshold;
    }

    public function decompress(string $payload): string{
        $compressAlgo = substr($payload, 0, 1);
        if($compressAlgo === "\x01") {
            $result = @zlib_decode($payload);
        }elseif($compressAlgo === "\x02"){
            $result = @zstd_uncompress($payload);
        }else{
            throw new DecompressionException("Invalid compression algorithm " . ord($compressAlgo));
        }
        if($result === false){
            throw new DecompressionException("Failed to decompress data");
        }
        return $result;
    }

    private static function zlib_encode(string $data, int $level) : string{
        return Utils::assumeNotFalse(zlib_encode($data, ZLIB_ENCODING_RAW, $level), "ZLIB compression failed");
    }

    public function compress(string $payload, int $algo = self::ZLIB): string{
        if($algo === self::ZLIB) {
            if(function_exists('libdeflate_deflate_compress')){
                return $this->willCompress($payload) ?
                    libdeflate_deflate_compress($payload, $this->level) :
                    self::zlib_encode($payload, 0);
            }
            return self::zlib_encode($payload, $this->willCompress($payload) ? $this->level : 0);
        }elseif($algo === self::ZSTD){
            return @zstd_compress($payload, $this->level);
        }
        throw new DecompressionException("Invalid compression algorithm " . $algo);
    }
}