<?php
  require('../../tfpdf/tfpdf.php');
  error_reporting(0);
  include('../../Model/detailGoods_M.php');
  include('../../Controller/detailGoods_C.php');
  include('../../Controller/sanpham.php');
  include('../../Model/connection.php');
  $detailGoods = new detailGoods_C;
  $products = new sanpham_controller;

  $pdf = new tFPDF();
  $pdf->AddPage("1");
  $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
  $pdf->SetFont('DejaVu','',14);

  $pdf->SetFillColor(193,299,252);
  $pdf->setAutoPageBreak(false);
  $pdf->Write(10, 'Hóa đơn nhập hàng');
  $pdf->Ln(10);

  $width_cell=array(12,150,50,50,40,40,40);

  $pdf->Cell($width_cell[0],10,'STT',1,0,'C',true);
  $pdf->Cell($width_cell[1],10,'Tên sản phẩm',1,0,'C',true);
  $pdf->Cell($width_cell[5],10,'Đơn giá',1,0,'C',true);
  $pdf->Cell($width_cell[4],10,'Số lượng',1,0,'C',true);
  $pdf->Cell($width_cell[6],10,'Thành tiền',1,1,'C',true);

  $fill=false;
  $i=0;
  $id = $_GET['id_goods'];
  $tongTienHoaDon=0;
  foreach ($detailGoods->detail_goods($id) as $list_goods) {
      $sanPham = $products->sanPhamDmThTheoID($list_goods['MaSanPham']);
      while ($row = mysqli_fetch_array($sanPham)) {
        $i++;
        $pdf->Cell($width_cell[0],10,$i,1,0,'C',$fill);
        $pdf->Cell($width_cell[1],10,$row['TenSanPham'],1,0,'C',$fill);
        $pdf->Cell($width_cell[4],10,number_format($list_goods['DonGia'], 0, ',', ','),1,0,'C',$fill);
        $pdf->Cell($width_cell[5],10,$list_goods['SoLuong'],1,0,'C',$fill);
        $pdf->Cell($width_cell[6],10,number_format($list_goods['ThanhTien'], 0, ',', ','),1,1,'C',$fill);
        $tongTienHoaDon+=$list_goods['ThanhTien'];
        $fill=!$fill;
      }
  }

  $pdf->Write(10, 'Tổng tiền: ');
  $pdf->Write(10, number_format($tongTienHoaDon, 0, ',', ','));
  $pdf->Write(10, ' VNĐ');
  $pdf->Ln(10);

  $pdf->Write(10, 'Thank you');
  $pdf->Ln(10);

  $pdf->Output();


?>