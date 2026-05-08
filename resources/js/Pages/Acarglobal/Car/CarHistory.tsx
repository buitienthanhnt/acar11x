import { Head, Link } from "@inertiajs/react"
import { Car, CarDetail } from "../types/CarType"
import ContentLayout from "../Layout/ContentLayout"

export default function CarHistory({ car }: { car: CarDetail }) {

  return (
    <ContentLayout>
      <Head title="lịch sử sửa chữa"></Head>
      <div className="container mx-auto p-2 space-y-2">
        <div className="bg-gray-400 p-2 rounded-md">
          <b>Biển số: {car.key}</b>
          <p>Loại xe:{car.suspension} - {car.type}</p>
          <p className="italic">VIN: {car.vin}</p>
          <b className="text-brown-700">Họ tên: {car.customer}({car.address})</b>
          <p className="font-semibold text-brown-700">sđt: {car.phone}</p>
        </div>

        <div className="bg-gray-300 rounded-xl p-2 gap-2 space-y-1">
          <div className="text-black font-semibold">
            Lịch sử sửa chữa:
          </div>
          {car?.car_fix.map((fixCar, index: number) => {
            return <Link href={route('car.fix.detail', { id: fixCar.id })} key={index} className={`block p-2 rounded-md ${fixCar.status === 'wait' ? 'bg-brown-200' : fixCar.status === 'processing' ? 'bg-blue-gray-300' : 'bg-green-400'}`}>
              <p className="font-semibold">Xe vào: {fixCar.created_at.slice(0, 10)}</p>
              {fixCar.status === 'done' && <p className=" font-semibold">Xe ra: {fixCar.updated_at.slice(0, 10)}</p>}
              <p className="text-white">Trạng thái: {fixCar.status_label}</p>
            </Link>
          })}
        </div>
      </div>
    </ContentLayout>
  )
}
