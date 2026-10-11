import { PaginateInterface } from "@/Pages/Amuaglobal/types/Paginate";
import { Head, Link } from "@inertiajs/react";
import ContentLayout from "../Layout/ContentLayout";
import { Paginate } from "@/Components/Custom";

const CarList = ({ cars: { data, current_page, last_page } }: { cars: PaginateInterface }) => {

  return (
    <>
      <Head title="danh sách xe"></Head>
      <div className="container mx-auto">
        <div className="grid grid-cols-2 bg-white rounded-xl p-2 gap-2">
          {data.map((car, index: number) => {
            return <Link href={route('car.history', { key: car.key })} key={index} className="bg-gray-400 p-2 rounded-md">
              <b className="bg-gray-100 flex p-1 rounded-md">Biển số: {car.key}</b>
              <p>Loại xe:{car.suspension} - {car.type}</p>
              <p className="italic">VIN: {car.vin}</p>
              <b className="text-brown-700">Họ tên: {car?.customer?.name}({car?.customer?.address})</b>
              <p className="font-semibold text-brown-700">sđt: {car?.customer?.phone}</p>
            </Link>
          })}
        </div>
        <Paginate pageSize={last_page} currentPage={current_page} url={window.location.href}></Paginate>
      </div>
    </>
  )
}

CarList.layout = (page: React.ReactNode) => <ContentLayout>{page}</ContentLayout>

export default CarList;