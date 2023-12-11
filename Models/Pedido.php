<?php

namespace Models;
use Lib\BaseDatos;
use Lib\Pages;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PDO;


class Pedido {
    private $id;
    private $nombre_cliente;
    private $email_cliente;
    private $medicamento;
    private $fecha_pedido;
    public  $errores;


    private BaseDatos $db;
    private Pages $pages;

    /**
     * Pedido constructor.
     * @param $id
     * @param $nombre_cliente
     * @param $email_cliente
     * @param $medicamento
     * @param $fecha_pedido
     * @param $errores
     * @param BaseDatos $db
     * @param Pages $pages
     */
    public function __construct(){
        $this->db = new BaseDatos();
        $this->pages = new Pages();
        $this->errores = [];
    }

    // Getters y Setters para las propiedades de la clase.

    /**
     * @return int|null
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * @param int|null $id
     */
    public function setId($id): void{
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNombreCliente(){
        return $this->nombre_cliente;
    }

    /**
     * @param string|null $nombre_cliente
     */
    public function setNombreCliente($nombre_cliente): void{
        $this->nombre_cliente = $nombre_cliente;
    }

    /**
     * @return string|null
     */
    public function getEmailCliente(){
        return $this->email_cliente;
    }

    /**
     * @param string|null $email_cliente
     */
    public function setEmailCliente($email_cliente): void{
        $this->email_cliente = $email_cliente;
    }

    /**
     * @return string|null
     */
    public function getMedicamento(){
        return $this->medicamento;
    }

    /**
     * @param string|null $medicamento
     */
    public function setMedicamento($medicamento): void{
        $this->medicamento = $medicamento;
    }

    /**
     * @return string|null
     */
    public function getFechaPedido(){
        return $this->fecha_pedido;
    }

    /**
     * @param string|null $fecha_pedido
     */
    public function setFechaPedido($fecha_pedido): void{
        $this->fecha_pedido = $fecha_pedido;
    }


    /**
     * Obtiene todos los pedidos.
     * @return array
     */
    public function getAll() {
        try {
            $stmt = $this->db->prepara("SELECT * FROM pedidos");
            $stmt->execute();
            $this->db->close();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Obtiene un pedido por su id.
     * @param $id
     * @return mixed
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepara("SELECT * FROM pedidos WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->db->close();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Guarda un pedido en la base de datos.
     * @return mixed
     */
    public function save() {
        try {
            $sql = "INSERT INTO pedidos (nombre_cliente, email_cliente, medicamento) VALUES (:nombre_cliente, :email_cliente, :medicamento)";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
            $stmt->bindValue(':email_cliente', $this->email_cliente, PDO::PARAM_STR);
            $stmt->bindValue(':medicamento', $this->medicamento, PDO::PARAM_STR);
            $stmt->execute();
            $this->db->close();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Elimina un pedido de la base de datos por su ID.
     * @param $id
     */
    public function delete() {
        try {
            $sql = "DELETE FROM pedidos WHERE id = :id";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Valida los datos del formulario de pedidos.
     * @param $nombre_cliente
     * @param $email_cliente
     * @param $medicamento
     * @return array
     */
    public function validarFormulario($nombre_cliente, $email_cliente, $medicamento) {

        $nombre_cliente = filter_var($nombre_cliente, FILTER_SANITIZE_STRING);
        $email_cliente = filter_var($email_cliente, FILTER_SANITIZE_EMAIL);
        $medicamento = filter_var($medicamento, FILTER_SANITIZE_STRING);

        if (empty($nombre_cliente)) {
            array_push($this->errores, "El nombre del cliente es obligatorio.");
        }
    
        if (empty($email_cliente) ||  !preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/', $email_cliente)) {
            array_push($this->errores, "El correo electrónico del cliente es obligatorio y debe ser válido.");
        }
    
        if (empty($medicamento)) {
            array_push($this->errores, "El nombre del medicamento es obligatorio.");
        }
    
        return $this->errores;
    }

    /**
     * Envia un correo electrónico al cliente.
     * @return void
     */
    public function enviarEmail() {
    /**
     * This example shows settings to use when sending via Google's Gmail servers.
     * This uses traditional id & password authentication - look at the gmail_xoauth.phps
     * example to see how to use XOAUTH2.
     * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
     */

    //Import PHPMailer classes into the global namespace
    //require '../vendor/autoload.php';

    //Create a new PHPMailer instance
    $mail = new PHPMailer();

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();

    //Enable SMTP debugging
    //SMTP::DEBUG_OFF = off (for production use)
    //SMTP::DEBUG_CLIENT = client messages
    //SMTP::DEBUG_SERVER = client and server messages
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    //Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
    //Use `$mail->Host = gethostbyname('smtp.gmail.com');`
    //if your network does not support SMTP over IPv6,
    //though this may cause issues with TLS

    //Set the SMTP port number:
    // - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
    // - 587 for SMTP+STARTTLS
    $mail->Port = 465;

    //Set the encryption mechanism to use:
    // - SMTPS (implicit TLS on port 465) or
    // - STARTTLS (explicit TLS on port 587)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = 'farmaciaphppablo@gmail.com';

    //Password to use for SMTP authentication
    $mail->Password = 'esgu meyv ppfa wutj';

    //Set who the message is to be sent from
    //Note that with gmail you can only use your account address (same as `Username`)
    //or predefined aliases that you have configured within your account.
    //Do not use user-submitted addresses in here
    $mail->setFrom('farmaciaphppablo@gmail.com', 'Farmacia de Pablo López Lozano');

    //Set an alternative reply-to address
    //This is a good place to put user-submitted addresses
    $mail->addReplyTo('replyto@example.com', 'First Last');

    //Set who the message is to be sent to
    $mail->addAddress($this->email_cliente, $this->nombre_cliente);
    // $mail->addAddress('plopezlozano12@gmail.com', 'YOU');

    //Set the subject line
    $mail->Subject = 'Ya ha llegado su pedido';

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    // $mail->msgHTML(file_get_contents('Views/pedido/correo.php'), __DIR__);
    ob_start();

    // Define the variables
    $nombre_cliente = $this->nombre_cliente;
    $id = $this->id;
    $medicamento = $this->medicamento;
    $fecha_pedido = $this->fecha_pedido;

    // Include the file and store the output in a variable
    include 'Views/pedido/correo.php';
    $html = ob_get_contents();

    // End output buffering
    ob_end_clean();

    // Use the output as the HTML body of the email
    $mail->msgHTML($html, __DIR__);

    //Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';

    //Attach an image file
    $mail->addAttachment('images/phpmailer_mini.png');

    //send the message, check for errors
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        $this->pages->render('pedido/verTodos');
        echo 'Message sent!';
        //Section 2: IMAP
        //Uncomment these to save your message in the 'Sent Mail' folder.
        #if (save_mail($mail)) {
        #    echo "Message saved!";
        #}
    }

    //Section 2: IMAP
    //IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
    //Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
    //You can use imap_getmailboxes($imapStream, '/imap/ssl', '*' ) to get a list of available folders or labels, this can
    //be useful if you are trying to get this working on a non-Gmail IMAP server.
    function save_mail($mail)
    {
        //You can change 'Sent Mail' to any other folder or tag
        $path = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';

        //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
        $imapStream = imap_open($path, $mail->Username, $mail->Password);

        $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
        imap_close($imapStream);

        return $result;
    }

    header('Location: ' . BASE_URL . 'pedido/verTodos');

    }


}