<?php
defined('BASEPATH') or die('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Export_model', 'export');
	}

	public function exportBarang()
	{
		$data = $this->export->exportBarang();

		$spreadsheet = new Spreadsheet;

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'No')
			->setCellValue('B1', 'Kode Barang')
			->setCellValue('C1', 'Nama Barang')
			->setCellValue('D1', 'Merk')
			->setCellValue('E1', 'List Variant')
			->setCellValue('F1', 'Harga')
			->setCellValue('G1', 'Limit')
			->setCellValue('H1', 'Kemasan');
		$kolom = 2;
		$nomor = 1;
		foreach ($data as $d) {

			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $kolom, $nomor)
				->setCellValue('B' . $kolom, $d->product_code)
				->setCellValue('C' . $kolom, $d->product_name)
				->setCellValue('D' . $kolom, $d->brand_name)
				->setCellValue('E' . $kolom, $d->list_variant)
				->setCellValue('F' . $kolom, $d->harga)
				->setCellValue('G' . $kolom, $d->limit_reminder)
				->setCellValue('H' . $kolom, $d->packing);
			$kolom++;
			$nomor++;
		}

		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Daftar Barang.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	public function templateBarang()
	{
		$data = $this->export->getBrand();

		$spreadsheet = new Spreadsheet;

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'kode_barang')
			->setCellValue('B1', 'nama_barang')
			->setCellValue('C1', 'variant')
			->setCellValue('D1', 'id_brand')
			->setCellValue('E1', 'harga')
			->setCellValue('F1', 'harga_apotik')
			->setCellValue('G1', 'harga_modal');
		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(1)->setTitle('List Merk')
			->setCellValue('A1', 'id_brand')
			->setCellValue('B1', 'nama_brand');
		$kolom = 2;
		foreach ($data as $d) {
			$spreadsheet->setActiveSheetIndex(1)
				->setCellValue('A' . $kolom, $d->id)
				->setCellValue('B' . $kolom, $d->nama);
			$kolom++;
		}
		$spreadsheet->setActiveSheetIndex(0);

		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Template Barang.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	public function templateAddStock()
	{
		$data = $this->export->getListVariant();

		$spreadsheet = new Spreadsheet;

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'id')
			->setCellValue('B1', 'kode_barang')
			->setCellValue('C1', 'nama_barang')
			->setCellValue('D1', 'merk')
			->setCellValue('E1', 'qty(pcs)')
			->setCellValue('F1', 'harga_beli')
			->setCellValue('G1', 'tanggal_expired');
		$kolom = 2;
		foreach ($data as $d) {

			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $kolom, $d->id)
				->setCellValue('B' . $kolom, $d->variant_code)
				->setCellValue('C' . $kolom, $d->product)
				->setCellValue('D' . $kolom, $d->brand_name);
			$kolom++;
		}

		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Template Tambah Stok.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}
