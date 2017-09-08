<?php
require_once '../includes/autoload.php';
session_start();

Auth::logout();

header("Location: ../index.php");