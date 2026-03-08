<?php
session_start();
session_destroy();
header("Location: ../public/login.html");
exit();
?>
```

---

## Step 3 — Test Everything

Open browser and go to:
```
http://localhost/mongodb-project/public/signup.html
```

---

## Your Final Folder Structure in VS Code:
```
mongodb-project/
    config/
        db.php
    public/
        signup.html
        login.html
        dashboard.php
    backend/
        signup.php
        login.php
        logout.php
    vendor/
    .env
    composer.json