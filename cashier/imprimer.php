<?php
require_once 'C:\wamp64\www\hotel\receptionist\clients\TCPDF-main\tcpdf.php';
require_once '../config/database.php';

// Get the reservation ID from the POST request
$id_reservation = isset($_POST['id_reservation']) ? intval($_POST['id_reservation']) : 0;

if ($id_reservation <= 0) {
    die('Invalid reservation ID.');
}

// Fetch reservation data from database
$sql = "SELECT reservation.*, client.nom_complet, client.telephone
        FROM reservation
        INNER JOIN client ON reservation.id_client = client.id_client
        WHERE reservation.id_reservation = ?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$id_reservation]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die('Reservation not found.');
}

// Initialize TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Hotel Management');
$pdf->SetTitle('Reservation Invoice');
$pdf->SetSubject('Reservation Invoice');
$pdf->SetKeywords('Reservation, Invoice, PDF');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Company Info (Header)
$logo = 'path/to/logo.png'; // Replace with the path to your logo
$htmlHeader = '
<table style="width: 100%;">
    <tr>
        <td style="width: 50%;">
            <img src="' . $logo . '" alt="Logo" style="width: 150px;">
        </td>
        <td style="width: 50%; text-align: right;">
            <h2>Hotel Management</h2>
            <p>67 Nahda, Ouled Teima, Morroco</p>
            <p>Email: PalaceRichKey@gmail.com | Phone: +2123456789</p>
        </td>
    </tr>
</table>
<hr>';

$pdf->writeHTML($htmlHeader, true, false, true, false, '');

// Invoice Details
$htmlContent = '
<h2 style="text-align: center;">Reservation Invoice</h2>
<table border="1" cellpadding="6" style="width: 100%; border-collapse: collapse;">
    <tr style="background-color: #f2f2f2;">
        <th style="width: 30%; text-align: left;">ID Reservation</th>
        <td style="width: 70%; text-align: left;">' . htmlspecialchars($reservation['id_reservation']) . '</td>
    </tr>
    <tr>
        <th style="width: 30%; text-align: left;">Client</th>
        <td style="width: 70%; text-align: left;">' . htmlspecialchars($reservation['nom_complet']) . '</td>
    </tr>
    <tr style="background-color: #f2f2f2;">
        <th style="width: 30%; text-align: left;">Telephone</th>
        <td style="width: 70%; text-align: left;">' . htmlspecialchars($reservation['telephone']) . '</td>
    </tr>
    <tr>
        <th style="width: 30%; text-align: left;">Arrival Date</th>
        <td style="width: 70%; text-align: left;">' . htmlspecialchars($reservation['date_arrivee']) . '</td>
    </tr>
    <tr style="background-color: #f2f2f2;">
        <th style="width: 30%; text-align: left;">Departure Date</th>
        <td style="width: 70%; text-align: left;">' . htmlspecialchars($reservation['date_depart']) . '</td>
    </tr>
    <tr>
        <th style="width: 30%; text-align: left;">Total Amount</th>
        <td style="width: 70%; text-align: left;">' . htmlspecialchars($reservation['montant_total']) . '</td>
    </tr>
</table>';

// Output HTML content
$pdf->writeHTML($htmlContent, true, false, true, false, '');

// Footer
$htmlFooter = '
<hr>
<p style="text-align: center;">Thank you for your reservation!</p>
<p style="text-align: center;">Please contact us if you have any questions regarding this invoice.</p>';

$pdf->writeHTML($htmlFooter, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('reservation_invoice.pdf', 'I');
?>
