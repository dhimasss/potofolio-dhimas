<?php

/*
 * Entry point serverless untuk Vercel.
 * Semua request non-statis diarahkan ke sini (lihat vercel.json),
 * lalu diteruskan ke front controller Laravel seperti biasa.
 */
require __DIR__.'/../public/index.php';
