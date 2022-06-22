<?php
class Reset extends MY_Controller {
    function demo() {
        if (DEMO) {
            $this->db->truncate('api_keys');
            $this->db->truncate('api_logs');
            $this->db->truncate('api_access');
            $this->db->truncate('api_limits');
            $this->db->truncate('badges');
            $this->db->truncate('captcha');
            $this->db->truncate('categories');
            $this->db->truncate('conversations');
            $this->db->truncate('fields');
            $this->db->truncate('fields_data');
            $this->db->truncate('groups');
            $this->db->truncate('login_attempts');
            $this->db->truncate('messages');
            $this->db->truncate('pages');
            $this->db->truncate('posts');
            $this->db->truncate('sessions');
            $this->db->truncate('settings');
            $this->db->truncate('topics');
            $this->db->truncate('users');
            $this->db->truncate('user_badges');
            $this->db->truncate('user_logins');
            $this->db->truncate('votes');
            $this->db->truncate('star_votes');
            $this->db->truncate('complains');

            $file = file_get_contents('./files/demo.sql');
            $this->db->conn_id->multi_query($file);
            $this->db->conn_id->close();
            $this->load->dbutil();
            $this->dbutil->optimize_database();

            exec('rm -f -r ' . FCPATH . '/uploads');
            exec('cp -r ' . FCPATH . '/../uploads ' . FCPATH . '/uploads');

            $msg = 'Demo has been reset :)';
        } else {
            $msg = 'Demo disabled :(';
        }

        echo '<!DOCTYPE html>
<html>
    <head>
        <title>Simple Forum Demo</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <style>
            html, body { height: 100%; }
            body { margin: 0; padding: 0; width: 100%; display: table; font-weight: 100; font-family: \'Lato\'; }
            .container { text-align: center; display: table-cell; vertical-align: middle; }
            .content { text-align: center; display: inline-block; }
            .title { font-size: 96px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">'.$msg.'</div>
            </div>
        </div>
    </body>
</html>
';
    }
}
