<?php
// ✅ Start session only if none exists
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("connection.php"); // your DB connection

// ✅ Restrict to admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Initialize chat history
if (!isset($_SESSION['azu_chat']) || !is_array($_SESSION['azu_chat'])) {
    $_SESSION['azu_chat'] = [];
}

// ✅ Function: Check trained responses from DB using partial match / keywords
function azu_trained_reply($user_input, $conn) {
    $user_input = strtolower(trim($user_input));

    $result = $conn->query("SELECT question, answer FROM azu_memory ORDER BY created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $question = strtolower($row['question']);

            $user_words = preg_split("/\s+/", $user_input); // split input into words
            foreach ($user_words as $word) {
                if ($word !== "" && strpos($question, $word) !== false) {
                    return $row['answer'];
                }
            }
        }
    }
    return null; // no match found
}

// ✅ Function: Rule-based responses
function azu_rule_based($q) {
    $q = strtolower($q);

    if (preg_match("/hello|hi|hey/i", $q)) {
        return "Hello 👋, I’m AZU — your AI assistant. How can I help you today?";
    }
    if (preg_match("/how are you/i", $q)) {
        return "I’m doing great, thanks for asking! 😊 What about you?";
    }
    if (preg_match("/users|members/i", $q)) {
        return "Currently, I can help you check user stats. Soon I’ll fetch live data from your database 📊.";
    }
    if (preg_match("/spins/i", $q)) {
        return "Spins are an exciting feature! 🎡 I can soon generate reports for welcome, weekly, referral, and premium spins.";
    }
    if (preg_match("/blogs/i", $q)) {
        return "Blogs help users share ideas 📝. I can assist in checking latest posts, edits, or deletions.";
    }
    if (preg_match("/tokens|balance/i", $q)) {
        return "Tokens are the premium spin currency 💎. I can track how much each user has.";
    }
    if (preg_match("/referrals?/i", $q)) {
        return "Referrals are a great growth strategy 👥. I’ll soon show referral stats directly from your DB.";
    }

    return null;
}

// ✅ Function: AI fallback (OpenAI API)
function azu_ai_reply($question) {
    $api_key = "YOUR_OPENAI_API_KEY"; // replace with your key

    $data = [
        "model" => "gpt-4o-mini",
        "messages" => [
            ["role" => "system", "content" => "You are AZU, an admin AI assistant for RefNet Agencies. Be helpful, clear, and friendly. Respond using paragraphs, headings, and bullet points if needed. Return HTML where applicable."],
            ["role" => "user", "content" => $question]
        ],
        "temperature" => 0.7
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $api_key
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $json = json_decode($response, true);
        return $json['choices'][0]['message']['content'] ?? "Sorry, I couldn’t get a response 😔.";
    } else {
        return "⚠️ AI service unavailable. Please try again later.";
    }
}

// ✅ Handle clear chat
if (isset($_POST['clear_chat'])) {
    $_SESSION['azu_chat'] = [];
    $_SESSION['azu_chat'][] = ["role" => "azu", "text" => "🗑️ Conversation cleared! (History saved in database)"];
}

// ✅ Handle chat input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $question = trim($_POST['question']);
    if ($question !== "") {
        $_SESSION['azu_chat'][] = ["role" => "user", "text" => htmlspecialchars($question)];

        // ✅ 1. Check trained Q&A
        $reply = azu_trained_reply($question, $conn);

        // ✅ 2. Rule-based
        if ($reply === null) {
            $reply = azu_rule_based($question);
        }

        // ✅ 3. AI fallback
        if ($reply === null) {
            $reply = azu_ai_reply($question);
        }

        $_SESSION['azu_chat'][] = ["role" => "azu", "text" => $reply];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AZU AI Assistant</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f9f9f9; margin:0; padding:20px; }
    .chat-box { background:white; padding:20px; border-radius:8px; max-width:800px; margin:auto; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    textarea { width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size:16px; }
    button { margin-top:10px; padding:10px 20px; background:#1abc9c; border:none; border-radius:6px; color:white; font-size:16px; cursor:pointer; }
    button:hover { background:#16a085; }
    .chat-log { margin-top:20px; max-height:400px; overflow-y:auto; padding-right:10px; }
    .msg { margin:10px 0; padding:10px 15px; border-radius:6px; max-width:70%; word-wrap:break-word; }
    .user { background:#dff9fb; margin-left:auto; text-align:right; }
    .azu { background:#ecfdf5; border-left:5px solid #1abc9c; }
    .top-bar { text-align:right; margin-bottom:10px; }
    .clear-btn { background:#d93025; color:white; border:none; padding:8px 14px; border-radius:6px; cursor:pointer; }
    .clear-btn:hover { background:#b1271b; }
  </style>
  <script>
  function confirmClear() {
      return confirm("⚠️ Are you sure you want to clear this conversation?\n(It will be removed from the screen but saved in history.)");
  }
  </script>
</head>
<body>

<div class="chat-box">
  <h2>🤖 AZU AI Assistant</h2>

  <div class="top-bar">
    <form method="POST" onsubmit="return confirmClear();" style="display:inline;">
      <button type="submit" name="clear_chat" class="clear-btn">🗑️ Clear Chat</button>
    </form>
  </div>

  <div class="chat-log">
    <?php
    $chatHistory = $_SESSION['azu_chat'] ?? [];
    if (!empty($chatHistory)):
        foreach ($chatHistory as $msg): ?>
            <div class="msg <?= $msg['role'] ?>"><?= $msg['text'] ?></div>
        <?php endforeach;
    else: ?>
        <div class="msg azu">🤖 Hello, I’m AZU! Start by typing your question below 👇</div>
    <?php endif; ?>
  </div>

  <form method="post">
    <textarea name="question" rows="3" placeholder="Type your question to AZU..."></textarea>
    <button type="submit">Send</button>
  </form>
</div>

</body>
</html>
