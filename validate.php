<?php
function loadVouchers() {
    $vouchers = array();
    if (($handle = fopen("vouchers.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $vouchers[] = array('code' => $data[0], 'active' => $data[1]);
        }
        fclose($handle);
    }
    return $vouchers;
}

function saveVouchers($vouchers) {
    $file = fopen("vouchers.csv", "w");
    foreach ($vouchers as $voucher) {
        fputcsv($file, $voucher);
    }
    fclose($file);
}

$response = array('valid' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['code'])) {
        $vouchers = loadVouchers();
        foreach ($vouchers as &$voucher) {
            if ($voucher['code'] == $data['code'] && $voucher['active'] == 'true') {
                $voucher['active'] = 'false';
                saveVouchers($vouchers);
                $response['valid'] = true;
                break;
            }
        }
    }
}

echo json_encode($response);
?>
