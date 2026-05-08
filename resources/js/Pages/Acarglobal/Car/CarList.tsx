import { PaginateInterface } from "@/Pages/Amuaglobal/types/Paginate";
import { Head, Link } from "@inertiajs/react";
import ContentLayout from "../Layout/ContentLayout";
import { Paginate } from "@/Components/Custom";
import { Car } from "../types/CarType";

const CarList = ({ cars: { data, current_page, last_page } }: { cars: PaginateInterface }) => {

  return (
    <>
      <Head title="danh sách xe"></Head>
      <div className="container mx-auto">
        <div className="grid grid-cols-2 bg-white rounded-xl p-2 gap-2">
          {data.map((item, index: number) => {
            return <Link href={route('car.history', { key: item.key })} key={index} className="bg-gray-400 p-2 rounded-md">
              <b className="bg-orange-200 flex p-1 rounded-md">Biển số: {item.key}</b>
              <p>Loại xe:{item.suspension} - {item.type}</p>
              <p className="italic">VIN: {item.vin}</p>
              <b className="text-brown-700">Họ tên: {item.customer}({item.address})</b>
              <p className="font-semibold text-brown-700">sđt: {item.phone}</p>
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