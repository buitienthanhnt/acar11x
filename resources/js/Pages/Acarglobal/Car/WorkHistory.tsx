import { Head, Link, router } from "@inertiajs/react";
import ContentLayout from "../Layout/ContentLayout"
import TextInputField from "../components/form/TextInputField";
import { useEffect, useRef, useState } from "react";
import { formatCurrency } from "@/Pages/Amuaglobal/Helper";
import { formatPrice } from "@/Helper/StringHelper";
import PrintBtn from "../components/element/PrintBtn";

const WorkHistory = ({ workTimes, employees }: any) => {
  const urlParams = new URLSearchParams(window.location.search);
  const params = Object.fromEntries(urlParams.entries());

  const [m, setM] = useState(params.m || new Date().getMonth() + 1);
  const [y, setY] = useState(params.y || new Date().getFullYear());
  const firstAction = useRef(true);

  const today = new Date();
  const month = today.getMonth() + 1;
  const year = today.getFullYear();

  const toDayString = `${year}-${month < 10 ? `0${month}` : month}-${today.getDate() < 10 ? `0${today.getDate()}` : today.getDate()}`;
  useEffect(() => {
    /**
     * pass for first render
     */
    if (firstAction.current) {
      firstAction.current = false;
      return;
    }

    router.get('/acar/work-history', { ...params, m, y }, {
      preserveState: true,
      preserveScroll: true,
    });
  }, [m, y])

  return (
    <ContentLayout>
      <Head title="tổng hợp chấm công"></Head>
      <PrintBtn title="In"></PrintBtn>
      <div className="flex gap-2 p-4 no-print">
        <TextInputField label={'Tìm theo tháng'} type='number' min={1} max={12} value={m} onChange={(e) => setM(e.target.value)}></TextInputField>
        <TextInputField label={'Tìm theo năm'} type='number' min={2025} max={2050} value={y} onChange={(e) => setY(e.target.value)}></TextInputField>
      </div>
      <div className="p-4">
        {(
          () => {
            let elements: any[] = [];
            const total: any = {};
            Object.entries(workTimes).forEach(
              ([key, value]) => {
                elements.push(<Link
                  href={'/acar/cham-cong'}
                  data={{ date: key }}
                  key={key}
                  className={`bg-blue-gray-100 rounded-md p-2 space-y-1 ${toDayString === key ? 'bg-purple-400' : ''}`}>
                  <p className="font-semibold ">Ngày: {key} </p>
                  <div className="">
                    {value.map((item: any) => {
                      total[item.id] = total[item.id] ? total[item.id] + item.time : item.time;
                      return <div key={item.id} className="flex justify-between">
                        <p>{employees[item.id].name} :</p>
                        <p>{item.time} giờ</p>
                      </div>
                    })}
                  </div>
                </Link>)
              })

            return (
              <div className="">
                <div className="grid grid-cols-5 gap-2 bg-blue-gray-50 rounded-md p-1">
                  {elements}
                </div>
                <div className="bg-purple-100 p-2 rounded-md mt-2">
                  <p className="font-semibold text-xl">Tổng hợp:</p>
                  <div className="font-semibold grid grid-cols-5 gap-3">
                    <div className="col-span-1 text-white">Nhân viên</div>
                    <div className="col-span-1 text-white">Giờ</div>
                    <div className="col-span-1 text-white">Số ngày công</div>
                    <div className="col-span-1 text-white">1 công</div>
                    <div className="col-span-1 text-white">Tổng lương</div>
                    {
                      (
                        () => {
                          let totalEmployee: any[] = [];
                          Object.entries(total).map(([key, value]) => {
                            totalEmployee.push(
                              <>
                                <div className="col-span-1">{employees[key].name}:</div>
                                <div className="col-span-1">
                                  {value}(giờ)
                                </div>
                                <div className="col-span-1">
                                  {value / 8}(công)
                                </div>
                                <div lassName="col-span-1">
                                  {employees[key].date_s}/công
                                </div>
                                <div className="col-span-1">
                                  = {formatPrice(value / 8 * employees[key].date_s)}
                                </div>
                              </>
                            )
                          });
                          return totalEmployee;
                        }
                      )()
                    }
                  </div>
                </div>
              </div>
            )
          }
        )()
        }
      </div>
    </ContentLayout>
  )
}

export default WorkHistory


{/* // <div>{employees[key].name} : {value} giờ = {Number((value / 8).toFixed(1))} cong</div> */ }