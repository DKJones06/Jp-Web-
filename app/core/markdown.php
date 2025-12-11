<?php

function md($text)
{
    // Escape HTML
    $text = htmlspecialchars($text);

    // Markdown formatting
    $rules = [
        '/\*\*(.+?)\*\*/s' => '<strong>$1</strong>',
        '/\*(.+?)\*/s' => '<em>$1</em>',
        '/`(.+?)`/s' => '<code>$1</code>',
        '/\n\# (.+)/' => '<h1>$1</h1>',
        '/\n\## (.+)/' => '<h2>$1</h2>',
        '/\n\### (.+)/' => '<h3>$1</h3>',
        '/\n\- (.+)/' => '<li>$1</li>',
    ];

    foreach ($rules as $regex => $replace) {
        $text = preg_replace($regex, $replace, $text);
    }

    // Wrap list items
    $text = preg_replace('/(<li>.+<\/li>)/s', '<ul>$1</ul>', $text);

    // Convert line breaks
    return nl2br($text);
}
