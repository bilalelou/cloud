<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CloudServersProvider;

class CloudServer extends Model
{
    use HasFactory;

    

    public static function generateRandomDomain()
    {
        $extensions = [".ai",".app",".art",".best",".biz",".ca",".cc",".club",".co",".co.uk",".com",".cx",".de",".design",".dev",".eu",".fm",".fun",".gg",".host",".icu",".id",".in",".inc",".info",".io",".is",".life",".live",".me",".mx",".net",".nl",".online",".org",".ph",".pro",".pw",".shop",".space",".store",".tech",".to",".tv",".uk",".us",".vip",".website",".world",".xyz"];

        $characters = "abcdefghijklmnopqrstuvwxyz";

        $length = rand(3,10);

        $randomString = "";

        for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, strlen($characters) - 1)];

        return $randomString.$extensions[array_rand($extensions)];
    }
}
