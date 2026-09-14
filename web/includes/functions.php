<!-- START functions rxs -->
<?php

function loadPrefs($conn)
{
	$selectprefs = "SELECT `name`, `value` FROM `preferences` ORDER BY `prefCat` ";
	$queryprefs = mysqli_query($conn, $selectprefs);
	while ($rowprefs = mysqli_fetch_assoc($queryprefs)) {
		$prefs[$rowprefs["name"]] = $rowprefs["value"];
	}
	return $prefs;
}
?>
<!-- End loadprefs -->
<?php
function getCompanyName($prefs)
{
	return $prefs['prefCompanyName'];
}
function getSiteName($prefs)
{
	return $prefs['prefSiteName'];
}

function getAddressLong($prefs)
{
	$prefsAddressLong = '';
	$addressFields = array(
		'prefAddress1',
		'prefAddress2',
		'prefTown',
		'prefCounty',
		'prefCountry',
		'prefPostcode'
	);

	$addressParts = array();

	foreach ($addressFields as $field) {
		if (!empty($prefs[$field])) {
			$addressParts[] = $prefs[$field];
		}
	}

	$prefsAddressLong = implode(', ', $addressParts);

	return $prefsAddressLong;
}

function getAddressShort($prefs)
{
	$prefsAddressShort = '';

	$addressFields = array(
		'prefAddress1',
		'prefAddress2',
		'prefTown',
		'prefCounty',
		'prefCountry',
		'prefPostcode'
	);

	foreach ($addressFields as $field) {
		if (!empty($prefs[$field])) {
			$prefsAddressShort .= $prefs[$field] . "<br>";
		}
	}

	return rtrim($prefsAddressShort, "<br>");
}

function getAddressList($prefs)
{
	$prefsAddressList = '';

	$addressFields = array(
		'prefAddress1',
		'prefAddress2',
		'prefTown',
		'prefCounty',
		'prefCountry',
		'prefPostcode'
	);

	foreach ($addressFields as $field) {
		if (!empty($prefs[$field])) {
			$prefsAddressList .= "<li>" . $prefs[$field] . "</li>";
		}
	}

	return $prefsAddressList;
}

function getEmail($prefs)
{
	return $prefs['prefEmail'];
}
function getTel1($prefs)
{
	return $prefs['prefTel1'];
}
function getTel1Int($prefs)
{
	return $prefs['prefTel1Int'];
}
function getTel2($prefs)
{
	return $prefs['prefTel2'];
}
function getTel2Int($prefs)
{
	return $prefs['prefTel2Int'];
}
function getFax($prefs)
{
	return $prefs['prefFax'];
}
function getGoogleMap($prefs)
{
	return $prefs['prefGoogleMap'];
}
function getLogo($prefs)
{
	return  $prefs['prefLogo'];
}
function getTagline($prefs)
{
	return $prefs['prefTagline'];
}
?>
<!-- End loadprefs specifics -->
<?php
?>
<?php
/* General Functions*/

function valid_email($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


function flipdate($dt, $seperator_in, $seperator_out)
{
	return implode($seperator_out, array_reverse(explode($seperator_in, $dt)));
}


// Function to check if the uploaded file is a PDF and within the size limit | salva TDR - 11.02.2023
function checkAttachment($file)
{
	$allowedTypes = [
		'application/pdf',
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
	];
	$allowedExtensions = ['pdf', 'doc', 'docx'];
	$maxSize = 5000000; // 5 MB

	// Validate the file input
	if (!isset($file['type']) || !isset($file['size'])) {
		return 'Invalid file input.';
	}

	// Check MIME type using PHP's finfo class to prevent spoofing
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$fileType = $finfo->file($file['tmp_name']);

	// Check file type
	if (!in_array($fileType, $allowedTypes)) {
		return 'Invalid file type. Only PDF and Word files are allowed.';
	}
	// Check file extension
	if (!in_array(pathinfo($file['name'], PATHINFO_EXTENSION), $allowedExtensions)) {
		return 'Invalid file extension. Only PDF and Word files are allowed.';
	}

	// Check file size
	if ($file['size'] > $maxSize) {
		return 'File size exceeds the limit of 5 MB.';
	}

	return true;
}

/**
 * Function to upload the PDF file to the server
 * 
 * @param array $file
 * @return array
 */
function uploadPDF($file)
{
	$uploadPath = __DIR__ . '/../wccms/uploadedfiles/';

	// Generate a unique name for the file and sanitize the file name
	$fileName = time() . '_' . basename(preg_replace("/[^a-zA-Z0-9._-]/", '', $file['name']));
	$fullPath = $uploadPath . $fileName;

	// Move the uploaded file to the uploads folder
	if (move_uploaded_file($file['tmp_name'], $fullPath)) {
		return [
			'status' => true,
			'file_name' => $fileName
		];
	} else {
		return [
			'status' => false,
			'file_name' => $fileName,
			'full_path' => $fullPath,
			'message' => 'Failed to upload the file.'
		];
	}
}

// Function to send the email with attachment | salva TDR - 11.02.2023
function sendMailWithAttachment($to, $from, $subject, $message, $attachment = '', $attachment_name = '')
{
	$separator = md5(time());
	$eol = PHP_EOL;

	$headers = "From: " . $from . " \r\n";
	$headers .= "MIME-Version: 1.0 \r\n";
	// Bcc 
	$headers .= "Bcc: clients@truska.com \r\n";

	if ($attachment != '' && $attachment_name != '') {
		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
	} else {
		$headers .= "Content-Type: text/html; charset=ISO-8859-1 \r\n";
	}

	$msg = "<!DOCTYPE html>";
	$msg .= "<html lang=\"en\">";
	$msg .= "<head>";
	$msg .= "<meta charset=\"utf-8\">";
	$msg .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">";
	$msg .= "</head>";
	$msg .= "<body>";
	$msg .= $message;
	$msg .= "</body>";
	$msg .= "</html>";

	if ($attachment != '' && $attachment_name != '') {
		$attachment = chunk_split(base64_encode(file_get_contents($attachment)));

		$body = "Content-Transfer-Encoding: 8bit" . $eol;
		$body .= "This is a MIME encoded message." . $eol;

		$body .= "--" . $separator . $eol;
		$body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
		$body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
		$body .= $msg . $eol;

		$body .= "--" . $separator . $eol;
		$body .= "Content-Type: application/octet-stream; name=\"" . $attachment_name . "\"" . $eol;
		$body .= "Content-Transfer-Encoding: base64" . $eol;
		$body .= "Content-Disposition: attachment" . $eol . $eol;
		$body .= $attachment . $eol;
		$body .= "--" . $separator . "--";
	} else {
		$body = $msg;
	}

	return mail($to, $subject, $body, $headers, '-f' . $from);
}

/**
 * Security check function
 * 
 * This function is used to check the security of the parameters passed to the function
 * 
 * @param string $param The parameter to be checked
 * @param string $type The type of the parameter
 * @return string|boolean The parameter if it is safe, false otherwise
 */
function securityCheck($param, $type = null)
{
	switch ($type) {
		case 'number':
			if (is_numeric($param)) {
				return trim(mysqli_real_escape_string(DB::connection(), $param));
			} else {
				return false;
			}
		default:
			return trim(mysqli_real_escape_string(DB::connection(), $param));
	}
}

function showEditButton($frm, $id) {
    global $prefs, $baseURL;

    $allowedIPs = [
        $prefs['prefTruskaIP'],
        $prefs['prefCoderIP'],
        $prefs['prefClientIP'],
        $prefs['prefClient1IP']
    ];

    if (in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
        echo "<div class='edit-btn-wrapper'>";
        	echo "<p><a href='{$baseURL}/wccms/recordEditv{$prefs['prefCMSVer']}.php?frm={$frm}&id={$id}' class='btn btn-success btn-sm' target='_blank' rel='noopener'>";
        	echo "<span><i class='fa-solid fa-pen-to-square'></i></span></a></p>";
        echo "</div>";
    }
}



function calculateTitlePixelWidth($title) {
    // Approximate pixel widths for Arial Bold (Google's font for titles)
    $charWidths = [
        'W' => 12, 'M' => 11, 'I' => 7, 'i' => 4, 'l' => 5, 'm' => 10, 'w' => 10,
        'A' => 9, 'B' => 9, 'C' => 9, 'D' => 10, 'E' => 8, 'F' => 8, 'G' => 10, 'H' => 10,
        'J' => 7, 'K' => 9, 'L' => 8, 'N' => 10, 'O' => 10, 'P' => 9, 'Q' => 10, 'R' => 9,
        'S' => 9, 'T' => 8, 'U' => 10, 'V' => 9, 'X' => 9, 'Y' => 9, 'Z' => 9,
        'a' => 8, 'b' => 8, 'c' => 7, 'd' => 8, 'e' => 8, 'f' => 5, 'g' => 8, 'h' => 8,
        'j' => 4, 'k' => 8, 'n' => 8, 'o' => 9, 'p' => 8, 'q' => 8, 'r' => 6, 's' => 7,
        't' => 5, 'u' => 8, 'v' => 8, 'x' => 8, 'y' => 8, 'z' => 7,
        '0' => 9, '1' => 6, '2' => 9, '3' => 9, '4' => 9, '5' => 9, '6' => 9, '7' => 9,
        '8' => 9, '9' => 9,
        ' ' => 4, '-' => 5, '.' => 4, ',' => 4, ':' => 4, '_' => 6, '/' => 6, '|' => 4
    ];
    
    // Calculate total width of the title
    $pixelWidth = 0;
    foreach (str_split($title) as $char) {
        $pixelWidth += $charWidths[$char] ?? 8; // Default 8px if unknown
    }
    
    return $pixelWidth;
}
?>

<!-- END functions rxs -->
