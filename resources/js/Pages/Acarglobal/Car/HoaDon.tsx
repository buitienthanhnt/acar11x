import ContentLayout from "../Layout/ContentLayout";
import { Head, usePage } from "@inertiajs/react";
import { CarFix } from "../types/CarType";
import { useCallback, useMemo, useState } from "react";
import { formatPrice } from '@/Helper/StringHelper';

export default function HoaDon({ car_fix }: { car_fix: CarFix }) {

  const onPrint = useCallback(() => {
    window.print();
  }, [])

  return (
    <ContentLayout>
      <Head title={`báo giá ${car_fix.car.key}`}>
      </Head>
      <div className="px-10 py-2 mx-auto bg-gray-300 flex flex-col min-w-full flex-1 gap-2">
        <CompanyInfo></CompanyInfo>
        <p className="text-center text-xl font-bold uppercase my-1">báo giá sửa chữa</p>
        <hr className="my-1" style={{
          backgroundColor: 'black',
          height: 2,
          margin: 10,
        }}></hr>
        <CarInfo {...car_fix}></CarInfo>
        <hr className="my-1" style={{
          backgroundColor: 'black',
          height: 2,
          margin: 10,
        }}></hr>
        <WorkList {...car_fix}></WorkList>
        <Signature></Signature>
        <div className="no-print absolute right-1 top-1 bg-gray-600 hover:bg-gray-900 px-4 p-1 rounded-md text-white"
          onClick={onPrint}>
          In lệnh
        </div>
      </div>
    </ContentLayout>
  )
}

const CompanyInfo = () => {
  const { acar_config }: { acar_config: any } = usePage().props as any;

  return (
    <div className="grid grid-cols-2 ">
      <div>
        <p className="text-xl font-semibold">Công ty {acar_config?.company}</p>
        <b>Số điện thoại: {acar_config?.phone}</b>
        <p className="font-semibold">
          <i>Địa chỉ: {acar_config?.address}</i>
        </p>
        <b>MST: {acar_config?.mst}</b>
      </div>
      <div className="flex flex-col justify-end items-end">
        {/* <p className="text-center text-xl font-semibold">Logo cong ty</p> */}
        <i >Hà Nội, ngày &nbsp; &nbsp; tháng &nbsp; &nbsp; &nbsp; &nbsp; năm &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</i>
      </div>
    </div>
  )
}


const WorkList = ({ activities, vat }: CarFix) => {
  const totalPrice = useMemo(() => {
    return activities.reduce((total, activity) => total + activity.price * activity.qty, 0);
  }, [])

  return (
    <div className="flex flex-col">
      <b>Nội dung công việc và phụ tùng:</b>
      <div className="flex flex-col flex-1 mt-2">
        <div
          className="relative flex flex-col w-full h-full  shadow-md rounded-xl bg-clip-border">
          <table className="w-full text-left table-auto ">
            <thead>
              <tr>
                <th className="p-2 border border-gray-700 ">
                  <p className="block font-sans text-sm antialiased  leading-none font-semibold">
                    Nội dung
                  </p>
                </th>
                <th className="p-2 border border-gray-700 ">
                  <p className="block font-sans text-sm antialiased  leading-none font-semibold">
                    Giá(đơn vị: nghìn vnđ)
                  </p>
                </th>
                <th className="p-2 border border-gray-700 ">
                  <p className="block font-sans text-sm antialiased  leading-none font-semibold">
                    Số lượng
                  </p>
                </th>
                <th className="p-2 border border-gray-700 ">
                  <p className="block font-sans text-sm antialiased  leading-none font-semibold">
                    Thành tiền(đơn vị: nghìn vnđ)
                  </p>
                </th>
              </tr>
            </thead>
            <tbody>
              {activities.map((activity, index) =>
                <tr key={index}>
                  <td className="p-2 border border-gray-700 max-w-56">
                    <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                      {activity.title}
                    </p>
                  </td>
                  <td className="p-2 border border-gray-700">
                    <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                      {activity.price}({formatPrice(activity.price)})
                    </p>
                  </td>
                  <td className="p-2 border border-gray-700">
                    <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                      {activity.qty} {activity.dvt ? `(${activity.dvt})` : ''}</p>
                  </td>
                  <td className="p-2 border border-gray-700">
                    <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                      {activity.price * activity.qty}({formatPrice(activity.price * activity.qty)})
                    </p>
                  </td>
                </tr>
              )}
              <tr >
                <td className="p-2 border border-gray-700 max-w-56">
                  <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                    Tổng chi phí
                  </p>
                </td>
                <td className="p-2 "></td>
                <td className="p-2 "></td>
                <td className="p-2 border border-gray-700">
                  <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                    {totalPrice}({formatPrice(totalPrice)})
                  </p>
                </td>
              </tr>
              {!!vat && <tr >
                <td className="p-2 border border-gray-700 max-w-56">
                  <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                    VAT
                  </p>
                </td>
                <td className="p-2 border border-gray-700">({vat}%) = {Math.round(totalPrice * (vat / 100))}({formatPrice(Math.round(totalPrice * (vat / 100)))})</td>
                <td className="p-2 border border-gray-700">1</td>
                <td className="p-2 border border-gray-700">
                  <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                    {Math.round(totalPrice * (vat / 100))}({formatPrice(Math.round(totalPrice * (vat / 100)))})
                  </p>
                </td>
              </tr>}

              {!!vat && <tr >
                <td className="p-2 border border-gray-700 max-w-56">
                  <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                    Tổng hóa đơn
                  </p>
                </td>
                <td className="p-2 "></td>
                <td className="p-2 "></td>
                <td className="p-2 border border-gray-700">
                  <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                    {totalPrice + Math.round(totalPrice * (vat / 100))}({formatPrice(totalPrice + Math.round(totalPrice * (vat / 100)))})
                  </p>
                </td>
              </tr>}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}

const CarInfo = ({ car, status_label }: CarFix) => {
  return (
    <div className="flex bg-gray-300 rounded-md">
      <div className="flex flex-col flex-1">
        <b>Khách hàng: {car.customer}</b>
        <b>Số điện thoại: {car.phone}</b>
        <i className="font-semibold">Địa chỉ: {car.address}</i>
      </div>
      <div className="flex flex-col flex-1">
        <b>Biển số: {car.key}</b>
        <b>Loại: {car.suspension}-{car.type}</b>
        <b>VIN: {car.vin}</b>
      </div>
    </div>
  )
}

const Signature = () => {
  return (
    <div className="grid grid-cols-2 mt-4">
      <div>
        <div className="font-semibold text-center uppercase">Đại diện khách hàng</div>
        <div className="text-center text-sm italic">(Đồng ý sửa chữa theo báo giá trên)</div>
      </div>
      <div className="font-semibold text-center uppercase">Trưởng phòng dịch vụ</div>
    </div>
  )
}
