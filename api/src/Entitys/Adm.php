<?php
class Adm
{
    /**
     *   @param $Entity - Trae todas las entidades para tener a mano toda la informacion necesaria
     */
    public static function edit_business(array $Entity)
    {
        try {

            if ($Entity['User']->role == 'ADMIN') {
                $business_id = isset($_POST['edit_business_adm']) ? $_POST['edit_business_adm'] : 0;
                $sales_expenses = isset($_POST['business_sales_expenses']) ? 1 : 0;
                $business_clients = isset($_POST['business_clients']) ? 1 : 0;
                $business_products = isset($_POST['business_products']) ? 1 : 0;
                $business_catalog = isset($_POST['business_catalog']) ? 1 : 0;
                $busines_catalog_and_list = isset($_POST['busines_catalog_and_list']) ? 1 : 0;
                $business_products_max = $_POST['business_products_max'];

                
                // return $status ? Helper::success('Business update') : Helper::error('Hubo un error');
            }
        } catch (Exception $e) {
            Logger::error('Adm', 'Error in edit_business -> ' . $e->getMessage());
            return Helper::error('Hubo un error complejo. Por favor comuniquelo al administrador de la plataforma. CODE[adm_001]');
        }
    }
    public static function get_view_business($business_id)
    {
        try {
            $data = DB::get(['catalog_and_list'], 'business_data', ['business_id' => $business_id]);
            if(!$data) return false;
            if(count($data) > 1){
                throw new Exception('Al cargar todas las business en el panel de adm está trayendo más de una business_data');
            }
            $data = $data[0];
            $data = json_decode($data['catalog_and_list'], true);
            return $data;
        } catch (Exception $e) {
            Logger::error('Adm', 'Error in get_view_business -> ' . $e->getMessage());
        }
    }
    public static function get_count_products($business_id)
    {
        try {
            $data = DB::get(['*'], 'm_products', ['business_id' => $business_id]);
            if(!$data) $data = [];
            $count = 0;
            foreach ($data as $key => $val) {
                $count++;
            }
            return $count;
        } catch (Exception $e) {
            Logger::error('Adm', 'Error in get_count_products -> ' . $e->getMessage());
        }
    }
    /**
     * Method for run scripts in instances
     */
    public static function scriptADM(): ?string
    {
        try {
            if ($_SESSION['User']->role != 'ADMIN') {
                return null;
            }


            return Helper::success("Script ejecutado.");
        } catch (Exception $e) {
            Logger::error('ADM', 'Error in scriptADM -> ' . $e->getMessage());
            return Helper::error("No se pudo ejecutar el Script");
        }
    }
}
