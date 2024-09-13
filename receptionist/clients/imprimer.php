<?php
require_once "../../config/database.php";
require_once 'TCPDF-main/tcpdf.php';

// Initialize TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Client Register');
$pdf->SetSubject('Client Register');
$pdf->SetKeywords('Client, Register, PDF');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 11);

// HTML content for the table
$html = '<h2>Client registre</h2>';
$html .= '<table border="1">';
$html .= '<thead>
            <tr>
                <th>ID Client</th>
                <th>Name </th>
                <th>Gender</th>
                <th>Âge</th>
                <th>Arrival date</th>
                <th>Date of departure</th>
                <th>N° of rooms</th>
                <th>Price (DH)</th>
            </tr>
          </thead>
          <tbody>';

// Fetch data from database
$sql = "SELECT client.id_client, client.nom_complet, client.sexe, client.age, 
               reservation.date_arrivee, reservation.date_depart, 
               chambre.numero_chambre, reservation.montant_total
        FROM client
        INNER JOIN reservation ON client.id_client = reservation.id_client
        INNER JOIN chambre ON reservation.id_chambre = chambre.id_chambre";
$stmt = $connexion->prepare($sql);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_OBJ);

if ($clients) {
    foreach ($clients as $client) {
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($client->id_client) . "</td>";
        $html .= "<td>" . htmlspecialchars($client->nom_complet) . "</td>";
        $html .= "<td>" . htmlspecialchars($client->sexe) . "</td>";
        $html .= "<td>" . htmlspecialchars($client->age) . "</td>";
        $html .= "<td>" . htmlspecialchars($client->date_arrivee) . "</td>";
        $html .= "<td>" . htmlspecialchars($client->date_depart) . "</td>";
        $html .= "<td>" . htmlspecialchars($client->numero_chambre) . "</td>";
        $html .= "<td>" . htmlspecialchars($client->montant_total) . "</td>";
        $html .= "</tr>";
    }
}

$html .= '</tbody></table>';

// Output HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('client_register.pdf', 'D');
?>
