<?php
  require('../../tfpdf/tfpdf.php');
  error_reporting(0);
  include('../../Controller/c_order.php');
  include('../../Model/m_order.php');
  include('../../Controller/sanpham.php');
  include('../../Model/connection.php');
  $order = new order_c;
  $products = new sanpham_controller;

  $pdf = new tFPDF();
  $pdf->AddPage("0");
  $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
  $pdf->SetFont('DejaVu','',14);

  $pdf->SetFillColor(193,299,252);
  $pdf->setAutoPageBreak(false);
  $pdf->Write(10, 'Đơn Hàng');
  $pdf->Ln(10);

  $width_cell=array(12,150,30,40,40);

  $pdf->Cell($width_cell[0],10,'STT',1,0,'C',true);
  $pdf->Cell($width_cell[1],10,'Tên sản phẩm',1,0,'C',true);
  $pdf->Cell($width_cell[2],10,'Số lượng',1,0,'C',true);
  $pdf->Cell($width_cell[3],10,'Đơn giá',1,0,'C',true);
  $pdf->Cell($width_cell[4],10,'Thành tiền',1,1,'C',true);

  $fill=false;
  $i=0;
  $id = $_GET['id_order'];
  $tongTienHoaDon=0;
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $i++;
      $pdf->Cell($width_cell[0],10,$i,1,0,'C',$fill);
      $pdf->Cell($width_cell[1],10,$item['ProductName'],1,0,'C',$fill);
      $pdf->Cell($width_cell[2],10,$item['Quantity'],1,0,'C',$fill);
      $pdf->Cell($width_cell[3],10,number_format($item['Price'], 0),1,0,'C',$fill);
      $pdf->Cell($width_cell[4],10,number_format($item['Price'] * $item['Quantity'], 0),1,1,'C',$fill);
      $tongTienHoaDon+=$thanhTien;
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