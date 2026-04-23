import React, { FunctionComponent } from "react";
import ContentLayout from "../Layout/ContentLayout";
import { CarFix as CarFixType } from "../types/CarType";
import { PlusCircleIcon } from "@heroicons/react/24/solid";
import TextInputField from "../components/form/TextInputField";

const CarFix: FunctionComponent<{ carFix: CarFixType }> = ({ carFix: { car, status } }) => {

  const onAddItem = () => {
    console.log(123);

  }

  return (
    <div className="container mx-auto bg-white flex-1 ">
      <div>
        <p>info of car repair:</p>
        <p>{car.customer}</p>
        <p>{car.phone}</p>
        <p>{car.address}</p>
        <p>{status}</p>
      </div>

      <div className="grid grid-cols-2 gap-2">
        <div className="bg-blue-gray-400">
          tinh trang, kiem tra:
          <p>chay dau dong co</p>
          <p>dong co hoat dong bi rung</p>
        </div>

        <div className="flex flex-col gap-2 relative">

          <div className="flex justify-center">
            xu ly va bao gia
          </div>
          <div className="flex justify-end">
            <PlusCircleIcon onClick={onAddItem} className="size-14 text-gray-500 hover:text-gray-900"></PlusCircleIcon>
          </div>

          <div className="flex">
            <div className="flex flex-1 justify-center font-semibold text-xl">
              noi dung
            </div>
            <div className="flex flex-1 justify-center font-semibold text-xl">
              chi phi:
            </div>
          </div>

          <div className="flex space-x-2">
            <div className="flex flex-1 justify-center">
              <textarea className="w-full rounded-md overflow-hidden"></textarea>
            </div>
            <div className="flex flex-1 justify-center">
              <TextInputField className="w-full"></TextInputField>
            </div>
          </div>

          <div className="flex">
            <div className="flex flex-1 justify-center">
              thya phot duoi truc co
            </div>
            <div className="min-w-[100px] flex justify-center">
              1200000000
            </div>
          </div>
        </div>
      </div>

    </div>
  );
}

CarFix.layout = (page: React.ReactNode) => <ContentLayout children={page} />

export default CarFix;