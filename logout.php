<?php
session_start();
session_unset();
session_destroy();
header("Location: index.html?view=login");
exit;