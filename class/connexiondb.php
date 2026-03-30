<?php

class ConnexionDB
{
    // ── Paramètres de connexion ────────────────────────────────────────────────
    private static string  $_host    = 'localhost';
    private static string  $_dbname  = 'gestion_etudiants';
    private static string  $_user    = 'root';
    private static string  $_pwd     = '';
    private static string  $_charset = 'utf8mb4';

    // ── Instance PDO unique (singleton) ───────────────────────────────────────
    private static ?PDO $_bdd = null;

    // ── Constructeur privé : empêche l'instanciation directe ──────────────────
    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            self::$_host,
            self::$_dbname,
            self::$_charset
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lève des exceptions sur erreur SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Retourne des tableaux associatifs
            PDO::ATTR_EMULATE_PREPARES   => false,                   // Requêtes préparées natives (anti-injection)
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',     // Force l'encodage côté MySQL
        ];

        try {
            self::$_bdd = new PDO($dsn, self::$_user, self::$_pwd, $options);
        } catch (PDOException $e) {
            error_log('[ConnexionDB] ' . $e->getMessage());
            die('Erreur de connexion à la base de données.');
        }
    }

    // ── Empêche le clonage et la désérialisation du singleton ─────────────────
    private function __clone() {}
    public function __wakeup(): void
    {
        throw new \Exception('La désérialisation du singleton est interdite.');
    }

    // ── Point d'accès global à l'instance PDO ─────────────────────────────────
    public static function getInstance(): PDO
    {
        if (self::$_bdd === null) {
            new self();
        }
        return self::$_bdd;
    }
}