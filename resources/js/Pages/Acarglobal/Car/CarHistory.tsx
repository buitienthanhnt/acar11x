import { Head, Link } from "@inertiajs/react"
import { Car, CarDetail } from "../types/CarType"

export default function CarHistory({ car }: { car: CarDetail }) {

  return (
    <>
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
            return <Link href={route('car.fix.detail', { id: fixCar.id })} key={index} className="block bg-gray-400 p-2 rounded-md">
              <p className="text-brown-600">Xe vào: {fixCar.created_at.slice(0, 10)}</p>
              <p className="text-brown-600">Hoàn thành: {fixCar.updated_at.slice(0, 10)}</p>
              <p>Trạng thái: {fixCar.status_label}</p>
            </Link>
          })}
        </div>
      </div>
    </>
  )
}