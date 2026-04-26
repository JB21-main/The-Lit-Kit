<?php
  session_start();

  if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit();
}

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get selected genres from form
    $genre1 = $_POST['genre1'];
    $genre2 = $_POST['genre2'];
    $genre3 = $_POST['genre3'];

    require_once 'db_connect.php';

    $userID = $_SESSION['user_id'];

    $delete = $conn->prepare("DELETE FROM prefers WHERE userID = ?");
    $delete->bind_param("i", $userID);
    $delete->execute();

    $genres = [$genre1, $genre2, $genre3];

    foreach ($genres as $gName) {

        // Get genreID from genre name
        $getID = $conn->prepare("SELECT genreID FROM genres WHERE genreName = ?");
        $getID->bind_param("s", $gName);
        $getID->execute();
        $result = $getID->get_result();

        if ($row = $result->fetch_assoc()) {
            $genreID = $row['genreID'];

            // Insert into prefers table
            $insert = $conn->prepare("INSERT INTO prefers (userID, genreID) VALUES (?, ?)");
            $insert->bind_param("ii", $userID, $genreID);
            $insert->execute();
        }
    }

  // Redirect after update
  header("Location: mainPage.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>The Lit Kit — Discover Literature</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --dark: #1a1a1a;
      --border: #e0e0e0;
      --input-border: #c8c8c8;
      --bg: #ffffff;
    }

    html, body { height: 100%; }

    body {
      font-family: 'EB Garamond', Georgia, serif;
      background: var(--bg);
      color: var(--dark);
      display: flex;
      flex-direction: column;
    }

    /* layout for top header */
    .top-bar {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 18px 60px;
      border-bottom: 1px solid var(--border);
      flex-shrink: 0;
    }

    .logo { text-decoration: none; }

    .logo-text {
      font-family: 'Playfair Display', Georgia, serif;
      font-style: italic;
      font-size: 2.0rem;
      color: var(--dark);
      letter-spacing: 0.01em;
    }

    /* main split layout */
    .split {
      display: flex;
      flex: 1;
    }

    .split-left {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 70px;
    }

    .form-wrap {
      width: 100%;
      max-width: 560px;
    }

    .form-title {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 3.6rem;
      font-weight: 400;
      line-height: 1.2;
      color: var(--dark);
      margin-bottom: 32px;
    }

    .divider {
      border: none;
      border-top: 1px solid var(--input-border);
      margin-bottom: 32px;
    }

    .subtitle {
      font-size: 1.3rem;
      color: var(--dark);
      margin-bottom: 42px;
    }

    .dropdown-row {
      display: flex;
      align-items: center;
      gap: 24px;
      margin-bottom: 34px;
    }

    .dropdown-row label {
      font-size: 1.25rem;
      color: var(--dark);
      white-space: nowrap;
      min-width: 148px;
    }

    .select-wrap {
      flex: 1;
      position: relative;
    }

    /* dropdown styling */
    .select-wrap select {
      width: 100%;
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.15rem;
      padding: 14px 44px 14px 16px;
      border: 1px solid var(--input-border);
      border-radius: 3px;
      appearance: none;
      -webkit-appearance: none;
      background: #fff;
      color: var(--dark);
      cursor: pointer;
      outline: none;
      transition: border-color 0.2s;
    }

    .select-wrap select:focus { border-color: var(--dark); }

    .select-wrap::after {
      content: '';
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      width: 0;
      height: 0;
      border-left: 5px solid transparent;
      border-right: 5px solid transparent;
      border-top: 6px solid var(--dark);
      pointer-events: none;
    }

    /* next button styling */
    .btn-next {
      display: inline-block;
      margin-top: 20px;
      background: var(--dark);
      color: #fff;
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.2rem;
      letter-spacing: 0.04em;
      padding: 16px 72px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s;
    }

    .btn-next:hover { background: #333; transform: translateY(-1px); }
    .btn-next:active { transform: translateY(0); }

    .split-right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0ece6;
      color: #aaa;
      font-family: 'EB Garamond', Georgia, serif;
      font-size: 1.3rem;
      letter-spacing: 0.08em;
    }
  </style>
</head>
<body>

  <!-- logo at the top -->
  <header class="top-bar">
    <a href="#" class="logo">
      <span class="logo-text">The Lit Kit</span>
    </a>
  </header>

  <!-- left and right sections -->
  <div class="split">

    <!-- genre dropdowns on the left -->
    <div class="split-left">
      <div class="form-wrap">
        <h1 class="form-title">Discover Literature<br>You'll Love</h1>
        <hr class="divider" />
        <p class="subtitle">Choose your top 3 genres:</p>

        <form method = "POST">
        <div class="dropdown-row">
          <label for="genre1">First Choice:</label>
          <div class="select-wrap">
            <select id="genre1" name="genre1" required>
              <option value="" disabled selected></option>
              <option>Journalism</option>
              <option>Film & Psychology</option>
              <option>Feminist Literature</option>
              <option>Media Theory</option>
              <option>Philosophy</option>
              <option>History</option>
            </select>
          </div>
        </div>

        <div class="dropdown-row">
          <label for="genre2">Second Choice:</label>
          <div class="select-wrap">
            <select id="genre2" name="genre2" required>
              <option value="" disabled selected></option>
              <option>Journalism</option>
              <option>Film & Psychology</option>
              <option>Feminist Literature</option>
              <option>Media Theory</option>
              <option>Philosophy</option>
              <option>History</option>
            </select>
          </div>
        </div>

        <div class="dropdown-row">
          <label for="genre3">Third Choice:</label>
          <div class="select-wrap">
            <select id="genre3" name="genre3" required>
              <option value="" disabled selected></option>
              <option>Journalism</option>
              <option>Film & Psychology</option>
              <option>Feminist Literature</option>
              <option>Media Theory</option>
              <option>Philosophy</option>
              <option>History</option>
            </select>
          </div>
        </div>

        <button type="submit" class="btn-next">Next</button>
      </form>
      </div>
    </div>

    <!-- image goes here on the right -->
    <div class="split-right"><img src="../../images/librairie-romantique-1600.jpg" style="width:100%;"></div>

  </div>
  <script>
    const selects = [
        document.getElementById('genre1'),
        document.getElementById('genre2'),
        document.getElementById('genre3')
    ];

    selects.forEach(select => {
        select.addEventListener('change', function() {
            // Get all selected values
            const selectedValues = selects.map(s => s.value);

            // For each select, enable all then disable the ones picked by others
            selects.forEach(s => {
                Array.from(s.options).forEach(option => {
                    option.disabled = false; // reset first
                });

                selectedValues.forEach(val => {
                    if(val && s !== select || s === select) {
                        Array.from(s.options).forEach(option => {
                            if(option.value !== s.value && selectedValues.includes(option.value)) {
                                option.disabled = true;
                            }
                        });
                    }
                });
            });
        });
    });
  </script>


</body>
</html>