import { FunctionComponent, useState } from "react";
import { Deferred, Head, Link, router } from "@inertiajs/react";
import { HomeItem as HomeItemType } from "../types/Home";
import { RoomItem as RoomItemType } from "../types/Room";
import { CustomTimeTable, RoomItemGrid } from "../Components";
import { DropdownMenu } from "../Components";
import { listDateToArrayString } from "../Helper/DateTimeHelper";
import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";
import { PagePaginate } from "../types/Paginate";


const HomeItem = ({ home }: { home: HomeItemType }) => {
	return (
		<Link className="bg-green-200 shadow-md p-2 rounded-md flex gap-x-4" href={sprintf(Urls.homeDetail, [home.id])}>
			<div>
				<img src={home.image_path} alt="avata hotel" className="w-60 rounded-md" />
			</div>
			<div className="flex flex-col space-y-2">
				<h4 className="text-2xl text-blue-700 font-bold ">{home.name}</h4>
				<p className="text-xl font-semibold">{home.description}</p>
				<p className="italic text-purple-600 font-semibold">{home.district}</p>
			</div>
		</Link>
	)
}

type Props = {
	homes: PagePaginate,
	rooms: PagePaginate,
	filters: { [key: string]: string | string[] | any },
	allFilters: any[],
}

const HomeList: FunctionComponent<Props> = ({ homes, rooms, filters, allFilters }) => {

	return (
		<>
			<Head title="home"></Head>
			<div className="container mx-auto p-4 space-y-4">
				<div className=" bg-blue-gray-300 min-h-36 rounded-md p-4"></div>

				<div className="grid lg:grid-cols-5 bg-white lg:space-x-4 space-y-2 lg:space-y-0">
					<div className="col-span-1 lg:col-span-2">
						<HomeFilter filters={filters} allFilters={allFilters}></HomeFilter>
					</div>
					<div className="col-span-1 lg:col-span-3 flex flex-col gap-y-2">
						<p className="text-lg font-medium">Total: {homes.total}</p>
						{homes?.data.map(home => <HomeItem home={home} key={home.id.toString()}></HomeItem>)}
					</div>
				</div>
				<div className='h-[1px] bg-black my-2'></div>
				{/* <Deferred data="rooms" fallback={<div>Loading...</div>}> */}
				{rooms && <div className="gap-2">
					<div>
						Total: {rooms.total}
					</div>
					<h3 className="text-lg font-semibold text-purple-300">List of rooms:</h3>
					<div className="lg:p-4 grid grid-cols-1 md:grid-cols-2 gap-2">
						{rooms?.data.map(room => <div key={room.id}>
							<RoomItemGrid room={room as unknown as RoomItemType}></RoomItemGrid>
						</div>)}
					</div>
				</div>}
				{/* </Deferred> */}
			</div>
		</>
	)
}

const HomeFilter = ({ filters, allFilters }) => {
	const [dateSelected] = useState<Date[]>(!!filters && filters?.dates ? filters?.dates.map((d: string) => new Date(d)) || [] : []);

	const onFilterSubmit = (type: string, item: { ley: string, value: string }) => {
		const newFilter = {
			...filters,
			[type]: item.value  // thêm khóa và giá trị mới cho bộ lọc
		};

		// if (dateSelected.length) {
		// 	newFilter['date'] = listDateToArrayString(dateSelected);
		// }

		/**
		 * loại trừ khóa khi khóa đó chọn lại lần 2 cùng giá trị(bỏ chọn)
		 */
		if (filters?.[type] !== undefined && filters?.[type] === item.value) {
			delete newFilter[type];
		}

		// gửi yêu cầu thủ công.
		router.visit(window.location.href, {
			method: 'post',
			data: {
				filters: newFilter,
				page: undefined
			},
			queryStringArrayFormat: 'indices',
			replace: true,
			// preserveUrl: true,
			// forceFormData: true,
		})
	}

	const onDateSelect = (value: Date[]) => {
		// chuyển hướng thủ công.
		router.visit(window.location.href, {
			method: 'post',
			data: {
				filters: {
					...filters,
					dates: listDateToArrayString(value),
				},
				page: undefined
			},
		})
	}

	if (!allFilters) {
		return null;
	}

	return (
		<div className="flex flex-col gap-x-2 gap-y-3">
			{allFilters.map((filter, index) => {
				return <DropdownMenu key={index} type={filter.key} label={filter.label} data={filter.data} onChange={onFilterSubmit}></DropdownMenu>
			})}
			<CustomTimeTable
				selected={dateSelected}
				onChange={onDateSelect}
				minDate={new Date()}
			></CustomTimeTable>
		</div>
	)
}

export default HomeList;