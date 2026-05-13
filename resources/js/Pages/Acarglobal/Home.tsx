import { Head, Link, router, usePage, WhenVisible, } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";
import { CarFix } from "./types/CarType";
import { CustomTimeTable, Paginate } from "../Amuaglobal/Components";
import TextInputField from "./components/form/TextInputField";
import { useCallback, useEffect, useState, } from "react";
import { debounce } from "lodash";
import { Cog6ToothIcon, TrashIcon, } from "@heroicons/react/24/solid";
import { listDateToArrayString } from "../Amuaglobal/Helper";
import TopBar from "./Layout/TopBar";

export default function Home() {

    return (
        <div className="container mx-auto p-4">
            <Head title="trang chủ" />
            <TopBar></TopBar>
            <CarfixList></CarfixList>
            <Link href={'/acar/setting'} className="absolute left-10 bottom-10">
                <Cog6ToothIcon className="size-10 text-gray-600 hover:text-gray-900"></Cog6ToothIcon>
            </Link>
        </div>
    )
}

const CarfixList = ({ }: any) => {
    const urlParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlParams.entries());

    const { props: { car_fixs, calendar }, } = usePage() as any;
    const [seletedDate, setSelectedDate] = useState<Date[]>([]);

    useEffect(() => {
        const defaultDate = [];
        if (params.from) {
            defaultDate.push(new Date(params.from));
        }
        if (params.to) {
            defaultDate.push(new Date(params.to));
        }
        if (defaultDate.length) {
            setSelectedDate(defaultDate);
        }
    }, [])

    const onSearch = useCallback(debounce((value: string) => {
        if (!value || value.length > 2) {
            router.get('/acar', { ...params, search: value }, {
                preserveState: true,
                preserveScroll: true,
                only: ['car_fixs'],
            });
        }
    }, 260), [])

    const onChangeDate = useCallback((seletedDate: Date[]) => {
        setSelectedDate(seletedDate);

        router.get('/acar', { ...params, from: seletedDate[0], to: seletedDate[1] }, {
            preserveState: true,
            preserveScroll: true,
            only: ['car_fixs'],
        });
    }, [])

    return (
        <div className="flex flex-col gap-1 mt-4">
            <TextInputField placeholder="Nhập biển số" defaultValue={params.search} displayClass="justify-center"
                onChange={(e: any) => onSearch(e.target.value)}>
            </TextInputField>
            {calendar && <CustomTimeTable
                selected={seletedDate}
                onChange={onChangeDate}
            >
            </CustomTimeTable>}
            <CarfixFilter></CarfixFilter>
            <WhenVisible data="car_fixs" fallback={null}>
                {!!car_fixs?.data.length ?
                    car_fixs.data.map((car_fix: CarFix, index: number) => <CarfixItem key={index} {...car_fix}></CarfixItem>) :
                    <div className="text-gray-700 flex justify-center font-semibold text-lg p-4">!không có thông tin tìm kiếm</div>
                }
                {car_fixs && <Paginate pageSize={car_fixs.last_page} currentPage={car_fixs.current_page}></Paginate>}
            </WhenVisible>
        </div>
    )
}

const CarfixFilter = () => {

    const urlParams = new URLSearchParams(window.location.search);
    const status = Object.fromEntries(urlParams.entries()).status;

    return (
        <div className="flex justify-between mt-2">
            <Link data={{ status: undefined, page: 1, }} className={`text-white p-2 px-3 rounded-lg ${status === undefined ? 'bg-blue-600' : 'bg-gray-800'}`}>Tất cả xe</Link>
            <Link data={{ status: 'wait', page: 1, }} className={`text-white p-2 px-3 rounded-lg ${status === 'wait' ? 'bg-blue-600' : 'bg-gray-800'}`}>Đang chờ báo giá</Link>
            <Link data={{ status: 'processing', page: 1, }} className={`text-white p-2 px-3 rounded-lg ${status === 'processing' ? 'bg-blue-600' : 'bg-gray-800'}`}>Đang thực hiện</Link>
            <Link data={{ status: 'done', page: 1, }} className={`text-white p-2 px-3 rounded-lg ${status === 'done' ? 'bg-blue-600' : 'bg-gray-800'}`}>Hoàn thành</Link>
        </div>
    )
}

const CarfixItem = ({ id, car, created_at, updated_at, status_label, status }: CarFix) => {
    return (
        <Link href={route('car.fix.detail', { id: id })}
            className={`p-2 rounded-md flex ${status === 'wait' ? 'bg-brown-200' : status === 'processing' ? 'bg-blue-gray-300' : 'bg-green-400'} space-x-4`}
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
                <p className="text-white font-semibold">Xe vào: {created_at.slice(0, 10)}</p>
                <p className="font-semibold">Cập nhật: {updated_at.slice(0, 10)}</p>
            </div>
            <div className="flex items-center">
                <Link
                    href={route('car.fix.remove', { id: id })}
                    as={'button'}
                    method="delete"
                    onBefore={() => confirm(`Bạn có chắc chắn muốn xóa ${car.key} ra khỏi danh sách sửa chữa không?`)}
                    only={['car_fixs']}
                >
                    <TrashIcon className="size-8 hover:text-red-900"></TrashIcon>
                </Link>
            </div>
        </Link>
    )
}
