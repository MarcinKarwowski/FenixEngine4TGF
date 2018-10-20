<?php


return array(
    // menu
    'menu-header' => 'GŁÓWNE MENU',
    'menu-dashboard' => 'Pulpit',
    'menu-config' => 'Konfiguracja silnika',
    'menu-info' => 'Informacje o systemie',
    'menu-update' => 'Aktualizacja',
    'menu-reset' => 'Usuń grę',
    'menu-after_reset' => 'Usunięto grę',
    'menu-articles' => 'Artykuły',
    'menu-media' => 'Media',
    'menu-wikipedia' => 'Wikipedia',
    'menu-notify' => 'Powiadomienia',
    'menu-system_menu' => 'Konserwacja',
    'menu-system_content' => 'Treści',
    'menu-menu_permissions' => 'Uprawnienia',
    // dashboard
    'dashboard-desc' => 'Zbiorcze informacje na temat gry ',
    'dashboard-lets-configure' => 'Musisz skonfigurować grę. Wybierz jeden z silników wylistowanych poniżej aby przejść do konfiguracji.',
    'dashboard-no-games' => 'Na liście nie ma gier',
    'dashboard-game-no-exist' => 'Wybrana gra nie znajduje się na liście gier',
    'dashboard-one-game' => 'Wyboru gry możesz dokonać tylko raz',
    'dashboard-registered-users' => 'Stworzonych kont',
    'dashboard-created_chars' => 'Stworzonych postaci',
    'dashboard-more-info' => 'Więcej informacji',
    'dashboard-register_at' => 'Zarejestrowany od ',
    'dashboard-user_activities' => 'Aktywności',
    'dashboard-user_online' => 'Online',
    // forms validation
    'form-field_required' => 'Pole :field jest wymagane',
    'form-field_wrongdate' => 'Niepoprawna data',
    'form-field_url' => 'Pole :field musi być linkiem',
    'form-field_toolong' => 'Wpisany tekst ":field" jest za długi',
    'form-field_tooschort' => 'Wpisany tekst ":field" jest za krótki',
    'form-field_integer_required' => 'Wpisz liczbę',
    'form-field_alphanum_required' => 'Dozwolone są tylko litery i cyfry',
    'form-field_email_not_valid' => 'Niepoprawny adres email',
    // configuration
    'configuration-first-chose-game' => 'Zanim przejdziesz do konfiguracji wybierz silnik gry',
    'configuration-desc' => 'Teraz musisz skonfigurować grę aby tego dokonać wypełnij poniższy formularz',
    'configuration-success' => 'Konfiguracja została zapisana',
    'configuration-emailconf' => 'Konfiguracja emaila gry',
    'configuration-email_smtp' => 'Konfiguracja serwera SMTP (opcjonalna)',
    'configuration-custom_el' => 'Konfiguracja dodatkowych elementów',
    'configuration-custom_code' => 'Własny kod (html+css+js)',
    // configuration form
    'configuration-game_title' => 'Tytuł gry',
    'configuration-game_description' => 'Opis gry',
    'configuration-layout' => 'Wygląd strony',
    'configuration-template' => 'Podstawowy wygląd strony',
    'configuration-template_text_color' => 'Podstawowy kolor tekstu w sesji',
    'configuration-template_text_color_desc' => 'Kolor musi być w formacie hex czyli sześć znaków np. ababab. Jeśli chcesz usunąć kolor usuń zawartość pola.',
    'configuration-default_template' => 'Domyślny szablon',
    'configuration-game_keywords' => 'Słowa kluczowe',
    'configuration-game_timetostart' => 'Data startu gry',
    'configuration-game_keywords_desc' => 'Słowa kluczowe oddzielone przecinkami',
    'configuration-game_url' => 'URL gry',
    'configuration-register_off' => 'Rejestracja wyłączona',
    'configuration-google_analitycs' => 'ID Google Analitycs',
    'configuration-email_sender' => 'Nazwa nadawcy emaili',
    'configuration-email' => 'Email gry',
    'configuration-servet_type' => 'Typ serwera email',
    'configuration-smtp_server' => 'Serwer',
    'configuration-smtp_port' => 'Port',
    'configuration-smtp_security' => 'Szyfrowanie',
    'configuration-smtp_username' => 'Użytkownik',
    'configuration-smtp_password' => 'Hasło',
    'configuration-level_off' => 'Wyłączyć poziom?',
    // system info
    'info-desc' => 'Informacje na temat systemów gry oraz serwera ',
    'info-more_desc' => 'W razie problemów z grą tutaj można poszukać przyczyn',
    'info-writable' => 'Zapisywalny',
    'info-unwritable' => 'Niezapisywalny (ustaw prawa 777 do katalogu)',
    'info-plug' => 'Włączony',
    'info-unplug' => 'Wyłączony',
    'info-system_control' => 'Kontrola systemu',
    'info-module' => 'Moduł',
    'info-value' => 'Wartość',
    'info-name' => 'Nazwa',
    'info-files' => 'Pliki i katalogi',
    // articles //
    'articles-text' => 'Edycja artykułów',
    'articles-text_desc' => 'Możesz tutaj dodawać oraz edytować artykuły',
    'articles-add' => 'Dodaj artykuł',
    'articles-title' => 'Tytuł',
    'articles-data' => 'Data publikacji',
    'articles-edit' => 'Edycja artykułu',
    'articles-validtype' => 'Wybierz poprawny typ',
    'articles-deleted' => 'Skasowano artykuł',
    // wikipedia
    'wikipedia-text' => 'Edycja artykułów wiki',
    'wikipedia-text_desc' => 'Artykuły nadrzędne stają się kategoriami',
    // game panel
    'game-panel' => 'Konfiguracja gry',
    'game-panel_edit' => 'Edytuj',
    // media
    'media-text' => 'Edycja plików',
    'media-text_desc' => 'Możesz tutaj dodawać oraz edytować pliki',
    'media-file' => 'Plik',
    'media-add' => 'Dodaj plik',
    'media-edit' => 'Edycja pliku',
    'media-wrong_type' => 'Niepoprawny format pliku',
    // update
    'update-current_version' => 'Twoja wersja',
    'update-next_version' => 'Najnowsza wersja',
    'update-no_information' => 'Brak informacji o możliwości aktualizacji',
    'update-wrong_file_download' => 'Nie udało się pobrać pliku z aktualizacją',
    'update-wrong_unzip' => 'Nie udało się rozpakować aktualizacji',
    'update-engine_done' => 'Zaktualizowano silnik do najnowszej wersji',
    'update-game_done' => 'Zaktualizowano grę do najnowszej wersji',
    'update-no_need' => 'Brak nowej wersji silnika',
    // users
    'users-list' => 'Lista użytkowników',
    'users-name' => 'Nick',
    'users-email' => 'Email',
    'users-id' => 'ID',
    'users-deleted' => 'Skasowano użytkownika',
    'users-deact' => 'Aktywacja',
    'users-activate' => 'Deaktywacja',
    'users-view' => 'Widok użytkownika',
    'users-no_exist' => 'Użytkownik nie istnieje',
    'users-group_user' => 'Grupa użytkowników',
    'users-group_admin' => 'Grupa administratorów',
    'users-group_label' => 'Grupa',
    'users-save_success' => 'Dane użytkownika zostały zapisane poprawnie',
    'users-save_error' => 'Nie udało się zapisać danych użytkownika',
    'users-active' => 'Konto aktywne',
    'users-characters' => 'Postacie przypisane do konta',
    // characters
    'characters-id' => 'ID',
    'characters-user' => 'Konto',
    'characters-name' => 'Imię postaci',
    'characters-equipment' => 'Ekwipunek postaci',
    'characters-spells' => 'Zaklęcia postaci',
    'characters-events' => 'Wydarzenia postaci',
    'characters-label' => 'Widok postaci',
    'characters-deleted' => 'Skasowano postać',
    'characters-level' => 'Poziom',
    'characters-gold' => 'Złoto',
    'characters-no_exist' => 'Postać nie istnieje',
    'characters-save_success' => 'Dane postaci zostały zapisane poprawnie',
    'characters-save_error' => 'Nie udało się zapisać danych postaci',
    // rpgsessions
    'chat-list' => 'Sesje',
    'chat-id' => 'ID',
    'chat-title' => 'Tytuł',
    'chat-owner' => 'Założyciel',
    'chat-deleted' => 'Skasowano sesję',
    'chat-cantdel' => 'Tego nie możesz skasować',
    // notifications
    'notify-text' => 'Globalne powiadomienia',
    'notify-text_desc' => 'Możesz tutaj wysłać do wszystkich graczy wiadomość',
    'notify-add' => 'Dodaj powiadomienie',
    'notify-edit' => 'Edycja powiadomienia',
    'notify-deleted' => 'Powiadomienie zostało skasowane',
    // permissions
    'perm-text' => 'Uprawnienia',
    'perm-text_desc' => 'Możesz tutaj ustawić uprawnienia użytkownikom',
    'perm-add' => 'Dodaj uprawnienia',
    'chat_npc' => 'Obsługa NPCów',
    'chat_delpost' => 'Kasowanie postów w sesji',
    'profile_edit' => 'Edycja ekwipunku, czarów, wydarzeń',
    'forum_moderator' => 'Moderacja na forum',
    'perm-edit' => 'Edycja uprawnień',
    'perm-cpermissions' => 'Dodatkowe uprawnienia',
    'perm-user_id' => 'ID użytkownika',
    'perm-deleted' => 'Uprawnienia zostały skasowane',
    'perm-saved' => 'Zmiany zostały zapisane',
);
