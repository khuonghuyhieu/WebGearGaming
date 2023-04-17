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

  $id = $_GET['id_order'];
  foreach ($order->search_order($id) as $list_search) {
    $pdf->Write(10, 'Khách hàng: ');
    $pdf->Write(10, $list_search['HoTen']);
  }
  $pdf->Ln(10);

  $width_cell=array(12,150,30,40,40);
  $pdf->Cell($width_cell[0],10,'STT',1,0,'C',true);
  $pdf->Cell($width_cell[1],10,'Tên sản phẩm',1,0,'C',true);
  $pdf->Cell($width_cell[2],10,'Số lượng',1,0,'C',true);
  $pdf->Cell($width_cell[3],10,'Đơn giá',1,0,'C',true);
  $pdf->Cell($width_cell[4],10,'Thành tiền',1,1,'C',true);

  $fill=false;
  $i=0;
  $tongTienHoaDon=0;
  foreach ($order->detail_order($id) as $list_detail) {
      $masp = $list_detail['MaSanPham'];
      $soLuong = $list_detail['SoLuong'];
      $donGia = $list_detail['DonGia'];
      $thanhTien = $list_detail['ThanhTien'];
      $sanPham = $products->sanPhamtheoID($masp);
      while ($row = mysqli_fetch_array($sanPham)) {
        $i++;
        $pdf->Cell($width_cell[0],10,$i,1,0,'C',$fill);
        $pdf->Cell($width_cell[1],10,$row['TenSanPham'],1,0,'C',$fill);
        $pdf->Cell($width_cell[2],10,$soLuong,1,0,'C',$fill);
        $pdf->Cell($width_cell[3],10,number_format($donGia, 0, ',', ','),1,0,'C',$fill);
        $pdf->Cell($width_cell[4],10,number_format($thanhTien, 0, ',', ','),1,1,'C',$fill);
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