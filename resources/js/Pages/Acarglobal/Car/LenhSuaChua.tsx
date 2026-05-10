import { Button } from "@material-tailwind/react";
import ContentLayout from "../Layout/ContentLayout";
import { Head, usePage } from "@inertiajs/react";
import { CarFix } from "../types/CarType";
import { CarFixInfo } from "./CarFix";
import { useCallback } from "react";
import PrintBtn from "../components/element/PrintBtn";

function LenhSuaChua({ car_fix }: any) {

  return (
    <>
      <Head title={`lệnh sửa chữa ${car_fix.id}`}></Head>
      <div className="px-10 py-2 mx-auto bg-gray-300 flex flex-col min-w-full flex-1 gap-2">
        <CompanyInfo></CompanyInfo>
        <p className="text-center text-xl font-bold uppercase my-1">lệnh sửa chữa(số: {car_fix.id})</p>
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
        <PrintBtn title="In lệnh"></PrintBtn>
      </div>
    </>
  )
}

LenhSuaChua.layout = (page: React.ReactNode) => <ContentLayout children={page}></ContentLayout>

export default LenhSuaChua;

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
        <i>Hà Nội, ngày &nbsp; &nbsp; &nbsp; &nbsp; tháng &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; năm &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</i>
      </div>
    </div>
  )
}

const WorkList = ({ activities }: CarFix) => {
  return (
    <div className="flex flex-col">
      <b>Nội dung công việc và phụ tùng:</b>
      <div className="flex flex-col flex-1">
        <div
          className="relative flex flex-col w-full h-full  shadow-md rounded-xl bg-clip-border">
          <table className="w-full text-left table-auto ">
            <thead>
              <tr>
                <th className="p-4 border border-gray-700 ">
                  <p className="block font-sans text-sm antialiased  leading-none font-semibold">
                    Nội dung
                  </p>
                </th>
                <th className="p-4 border border-gray-700 ">
                  <p className="block font-sans text-sm antialiased  leading-none font-semibold">
                    Số lượng
                  </p>
                </th>
                <th className="p-4 border border-gray-700 ">
                  <p className="block font-sans text-sm antialiased  leading-none font-semibold">
                    Nhân viên
                  </p>
                </th>
                <th className="p-4 border border-gray-700 ">
                  <p className="block font-sans text-sm antialiased  leading-none font-semibold">
                    Ghi chú
                  </p>
                </th>
              </tr>
            </thead>
            <tbody>
              {activities.map((activity, index) =>
                <tr key={index}>
                  <td className="p-4 border border-gray-700 max-w-56">
                    <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                      {activity.title}
                    </p>
                  </td>
                  <td className="p-4 border border-gray-700">
                    <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                      {activity.qty} {activity.dvt ? `(${activity.dvt})` : ''}
                    </p>
                  </td>
                  <td className="p-4 border border-gray-700">
                    <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                    </p>
                  </td>
                  <td className="p-4 border border-gray-700">
                    <p className="block font-sans text-sm antialiased  leading-normal font-semibold">
                    </p>
                  </td>
                </tr>
              )}
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
      <div className="font-semibold text-center uppercase">Phụ trách kỹ thuật</div>
      <div className="font-semibold text-center uppercase">Trưởng phòng dịch vụ</div>
    </div>
  )
}
