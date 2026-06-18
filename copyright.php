<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spectra Video - Copyright & Licensing</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div>
            <div>
                <h1>Spectra<span style="background-color: #0055FF;">Video</span></h1>
                <i>Broadcast Yourself™</i>
            </div>
            
            <p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-weight: bold;">
                        <img src="../images/<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile" style="width: 22px; height: 22px; border: 1px solid #999; object-fit: cover;">
                        Hello, 
                        <a href="channel/<?php echo htmlspecialchars($_SESSION['username']); ?>" style="text-decoration: none; color: #0033CC; font-weight: bold;">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        | <a href="settings.php">Settings</a>
                        | <a href="index.php?action=logout">Log Out</a>
                    </span>
                <?php else: ?>
                    <a href="register.php">Sign Up</a> | <a href="index.php">Log In</a> | <a href="#">Help</a>
                <?php endif; ?>
            </p>
        </div>
        
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="most-recent.php">Videos</a></li>
                <li><a href="channels.php">Channels</a></li>
                <li><a href="copyright.php">Copyright</a></li>
                <li><a href="upload.php">Upload</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section style="width: 100%;">
            <h2>Copyright & software Licensing Terms</h2>
            <p style="margin-bottom: 15px; color: #555;">Please review the legal distribution terms for the software driving Spectra Video below.</p>

            <div style="border: 1px solid #CCC; background: #FAFAF9; padding: 20px; font-family: monospace; font-size: 12px; line-height: 1.5; white-space: pre-wrap; overflow-y: auto; max-height: 450px; color: #222;">
Copyright (c) <?php echo date("Y"); ?> by Spectra * curiositydanube.duckdns.org
All Rights Reserved

ATTRIBUTION ASSURANCE LICENSE 

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the conditions below are met.

These conditions require a modest attribution to &lt;AUTHOR&gt; (the “Author”), who hopes that its promotional value may help justify the thousands of dollars in otherwise billable time invested in writing this and other freely available, open-source software.

1. Redistributions of source code, in whole or part and with or without modification (the “Code”), must prominently display this GPG-signed text in verifiable form.

2. Redistributions or Derivatives of the Code in binary form must be accompanied by this GPG-signed text in any documentation and, each time the resulting executable program or a program dependent thereon is launched, a prominent display (e.g., splash screen or banner text) of the Author’s attribution information, which includes:
(a) Name (“CuriosityDanube”),
(b) Professional identification (“Spectra”), and
(c) URL (“curiositydanube.duckdns.org”).

3. Neither the name nor any trademark of the Author may be used to endorse or promote products derived from this software without specific prior written permission.

4. Users are entirely responsible, to the exclusion of the Author and any other persons, for compliance with
(1) regulations set by owners or administrators of employed equipment,
(2) licensing terms of any other software, and
(3) local regulations regarding use, including those regarding import, export, and use of encryption software.

THIS FREE SOFTWARE IS PROVIDED BY THE AUTHOR “AS IS” AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR ANY CONTRIBUTOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL