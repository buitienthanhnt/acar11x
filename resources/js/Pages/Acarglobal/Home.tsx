import { Head, Link, router, usePage, WhenVisible, } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";
import { CarFix } from "./types/CarType";
import { Paginate } from "../Amuaglobal/Components";
import TextInputField from "./components/form/TextInputField";
import { useCallback, } from "react";
import { debounce } from "lodash";

export default function Home() {

  return (
    <div className="container mx-auto p-4">
      <div className="grid grid-cols-2 flex-1 p-4 bg-blue-gray-100 flex-col gap-2 rounded-lg">
        <Head title="acar home" />
        <Button variant="filled" onClick={() => {
          router.visit('/acar/xe-vao');
        }}>
          Xe vào
        </Button>
        <Button variant="filled" onClick={() => {
          router.visit('/acar/car-list');
        }}>
          Danh sách xe
        </Button>
      </div>
      <WhenVisible data="car_fixs" fallback={null}>
        <CarfixList></CarfixList>
      </WhenVisible>
    </div>
  )
}

const CarfixList = ({ }: any) => {
  const { props: { car_fixs }, } = usePage() as any;

  const urlParams = new URLSearchParams(window.location.search);
  const params = Object.fromEntries(urlParams.entries());

  const onSearch = useCallback(debounce((value: string) => {
    if (!value || value.length > 2) {
      router.get('/acar', { ...params, search: value }, {
        preserveState: true,
        preserveScroll: true,
        only: ['car_fixs'],
      });
    }
  }, 260), [])

  return (
    <div className="flex flex-col gap-1 mt-4">
      <TextInputField placeholder="Nhập biển số" defaultValue={params.search} displayClass="justify-center w-fit!important"
        onChange={(e: any) => onSearch(e.target.value)}></TextInputField>
      <CarfixFilter></CarfixFilter>
      {!!car_fixs?.data.length ?
        car_fixs.data.map((car_fix: CarFix, index: number) => <CarfixItem key={index} {...car_fix}></CarfixItem>) :
        <div className="text-gray-700 flex justify-center font-semibold text-lg p-4">!không có thông tin tìm kiếm</div>
      }
      {car_fixs && <Paginate pageSize={car_fixs.last_page} currentPage={car_fixs.current_page}></Paginate>}
    </div>
  )
}

const CarfixFilter = () => {

  const urlParams = new URLSearchParams(window.location.search);
  const status = Object.fromEntries(urlParams.entries()).status;

  return (
    <div className="flex justify-between mt-2">
      <Link data={{ status: undefined, page: 1, }} className={`text-white p-2 px-3 rounded-lg ${status === undefined ? 'bg-blue-600' : 'bg-gray-800'}`}>Tất cả xe</Link>
      <Link data={{ status: 'wait', page: 1, }} className={`text-white p-2 px-3 rounded-lg ${status === 'wait' ? 'bg-blue-600' : 'bg-gray-800'}`}>đang chờ báo giá</Link>
      <Link data={{ status: 'processing', page: 1, }} className={`text-white p-2 px-3 rounded-lg ${status === 'processing' ? 'bg-blue-600' : 'bg-gray-800'}`}>đang thực hiện</Link>
      <Link data={{ status: 'done', page: 1, }} className={`text-white p-2 px-3 rounded-lg ${status === 'done' ? 'bg-blue-600' : 'bg-gray-800'}`}>Hoàn thành</Link>
    </div>
  )
}

const CarfixItem = ({ id, car, created_at, updated_at, status_label, status }: CarFix) => {
  return (
    <Link href={route('car.fix.detail', { id: id })}
      className={`p-2 rounded-md flex ${status === 'wait' ? 'bg-brown-200' : status === 'processing' ? 'bg-blue-gray-300' : 'bg-green-400'}`}
    >
      <div className="flex-1">
        <p>{car.key}</p>
        <p>{car.suspension} - {car.type}</p>
      </div>
      <div className="flex-1">
        <p>{car.customer}({car.address})</p>
        <p>sdt: {car.phone}</p>
      </div>
      <div className="flex flex-col">
        <p className="font-semibold text-indigo-700">{status_label}</p>
        <p>Xe vào: {created_at.slice(0, 10)}</p>
        <p>Cập nhật: {updated_at.slice(0, 10)}</p>
      </div>
    </Link>
  )
}