<?php

namespace App\Service;

class HappyQuote
{
    public function getHappyMessage(): string
    {
        $quotes = [
            "Every day is a chance to learn something new!",
            "Keep going — your hard work will pay off!",
            "You’re doing amazing, don’t give up now!",
            "Success is the sum of small efforts repeated every day.",
            "Stay positive, work hard, and make it happen!"
        ];

        // Renvoie un message aléatoire
        return $quotes[array_rand($quotes)];
    }
}
