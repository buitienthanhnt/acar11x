import useMessage from '../hooks/useMessage';
import FlashMessage from "../Components/FlashMessage";
import { BaseLayout } from "../Layouts";
import { FooterWithLogo, HomeSpeed, NavbarDark } from "../Layouts/Components";
import { FunctionComponent, useState } from "react";
import { Head, Link, router, useRemember } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";
import { listDateToArrayString } from "../Helper/DateTimeHelper";
import { HomeDetail, HomeItemType } from "../types/Home";
import { RoomHome, } from "../types/Room";
import { PagePaginate } from "../types/Paginate";
import { Paginate, CustomTimeTable, RoomItemGrid, HomeMap } from "../Components";
import { FingerPrintIcon, MapPinIcon, XMarkIcon } from "@heroicons/react/24/solid";
import { SparklesIcon } from "@heroicons/react/24/solid";
import District from '../Components/Homes/District';
import Title from '../Components/Title';
import SwipeSingle from '../Components/Homes/SwipeSingle';

type Props = {
	homes: PagePaginate,
	rooms: PagePaginate,
	filters: { [key: string]: string | string[] | any },
	allFilters: any[],
}

const HomePage: FunctionComponent<Props> = ({ homes, rooms, filters, allFilters }) => {
	const { error, success } = useMessage();
	return (
		<BaseLayout>
			<Head title="Trang chủ">
				<style>
					{``}
				</style>
			</Head>
			<div className="flex flex-col sm:justify-center sm:pt-0 min-h-screen mx-auto 
						 dark:bg-gray-300 ">
				<NavbarDark navStyles={'rounded-none max-w-full'}></NavbarDark>
				<div className="relative h-0 flex justify-end overflow-y-visible">
					{(error || success) && <FlashMessage message={error || success} type={error ? 'error' : 'success'}
						className="absolute top-4 flex right-0 w-auto ">
					</FlashMessage>}
				</div>

				<div className="w-full mx-auto bg-gradient-to-r from-blue-gray-900 to-blue-gray-800 space-y-1">
					<div className="min-h-36 md:min-h-[480px] p-1 md:p-4 bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center">
						<div className="grid lg:grid-cols-6 xl:grid-cols-7 lg:space-x-4 space-y-2 lg:space-y-0">
							<div className="col-span-1 lg:col-span-2">
								<HomeFilter filters={filters} allFilters={allFilters}></HomeFilter>
							</div>
							<div className='lg:col-spans-4 xl:col-span-5 flex flex-col w-full space-y-1'>
								<p className='text-2xl font-semibold text-white invisible md:visible'>Trải nghiệm kỳ nghỉ tuyệt vời</p>
								<h3 className='text-white font-semibold text-xl invisible md:visible'>
									Combo khách sạn - vé máy bay - đưa đón sân bay giá tốt nhất
								</h3>
							</div>
						</div>
					</div>
				</div>

				<div className="flex flex-col flex-1 w-full p-1 mx-auto md:container">
					<div className="space-y-10 ">
						{filters?.district && homes && <HomeMap homes={homes.data as unknown as HomeDetail[]}></HomeMap>}
						{homes?.data &&
							<div className="grid lg:space-x-4 md:py-2 py-1 space-y-2 lg:space-y-0">
								<div className="col-span-1 lg:col-span-3 flex flex-col gap-y-1  md:gap-y-3 p-1 md:p-0">
									<Title title={`Nhà khách đề xuất: ${homes.total}`}></Title>
									<div className='grid md:grid-cols-3 lg:grid-cols-4 gap-1 md:gap-2 lg:gap-3 w-full'>
										{homes?.data.map(home => <HomeItem home={home as HomeItemType} selectedDates={filters?.dates} key={home.id.toString()}></HomeItem>)}
									</div>
									<Paginate pageSize={homes.last_page} currentPage={homes.current_page}
										mergeData={{ filters: filters || undefined }} linkProps={{
											method: 'get',
											preserveScroll: true,
											prefetch: ['hover',], // prefecth must be use in GET request only
										}}></Paginate>
								</div>
							</div>
						}
						{!!rooms?.data.length && <div className="gap-2 space-y-3 p-1 md:p-0 mt-4">
							<Title title={`Phòng nghỉ đề xuất: ${rooms.total}`}></Title>
							<div className="grid md:grid-cols-3 lg:grid-cols-4 gap-1 md:gap-2 lg:gap-3 w-full">
								{rooms?.data.map(room => <div key={room.id}>
									<RoomItemGrid room={room as unknown as RoomHome} selectedDates={filters?.dates}></RoomItemGrid>
								</div>)}
							</div>
							<Paginate pageSize={rooms.last_page} currentPage={rooms.current_page} pageName="room_page"
								mergeData={{ filters: filters || undefined }} linkProps={{
									method: 'get',
									preserveScroll: true,
									prefetch: ['hover',], // prefecth must be use in GET request only
								}}></Paginate>
						</div>}

						<div className='space-y-3 mt-4'>
							<Title title="Gợi ý xu hướng"></Title>
							<SwipeSingle></SwipeSingle>
						</div>

						<div className='space-y-3 mt-4'>
							<Title title="Gợi ý điểm đến yêu thích"></Title>
							<District></District>
						</div>
						<div className='h-2'></div>
					</div>
					<HomeSpeed></HomeSpeed>
				</div>
				<FooterWithLogo></FooterWithLogo>
			</div>
		</BaseLayout>
	);
}

export const HomeItem = ({ home, selectedDates }: { home: HomeItemType, selectedDates?: any }) => {

	return (
		<Link className="shadow-sm md:shadow-md hover:shadow-lg rounded-md gap-x-1 md:gap-x-4 flex md:flex-col 
			bg-gradient-to-r from-blue-gray-200 to-blue-gray-100 md:bg-transparent md:from-transparent md:to-transparent"
			queryStringArrayFormat={'brackets'}
			href={sprintf(Urls.homeDetail, [home.id])}
			data={{ selectedDates: selectedDates }}>
			<div >
				<img src={home.image_path} alt="avata hotel" className="max-w-40 md:max-w-60 lg:max-w-full h-full md:min-h-48 lg:min-h-60 rounded-md md:rounded-t-md md:rounded-b-none" />
			</div>
			<div className="flex flex-col justify-between gap-1 md:gap-2 p-1 md:py-2 h-full">
				<div>
					<h4 className="text-md lg:text-xl font-bold ">{home.name}</h4>
					<div className="flex gap-x-1 items-center">
						<SparklesIcon className="size-6 text-gray-800"></SparklesIcon>
						<p className="text-sm md:text-base lg:text-md font-semibold">{home.description}</p>
					</div>
				</div>
				<div className="flex">
					<MapPinIcon className="size-5 text-purple-500"></MapPinIcon>
					<p className="italic text-purple-600 font-semibold text-sm md:text-md">{home.district}</p>
				</div>
			</div>
		</Link>
	)
}

const HomeFilter = ({ filters, allFilters }) => {
	const [dateSelected] = useState<Date[]>(!!filters && filters?.dates ? filters?.dates.map((d: string) => new Date(d)) || [] : []);

	const [formState, setFormState] = useRemember({
		search: filters?.district || '',
	}, 'page.search')

	const onFilterSubmit = (type: string, item: { ley: string, value: string }) => {
		const newFilter = {
			...filters,
			[type]: item.value  // thêm khóa và giá trị mới cho bộ lọc
		};
		/**
		 * loại trừ khóa khi khóa đó chọn lại lần 2 cùng giá trị(bỏ chọn)
		 */
		if (filters?.[type] !== undefined && filters?.[type] === item.value) {
			delete newFilter[type];
		}
		// gửi yêu cầu thủ công.
		router.visit(window.location.pathname, {
			method: 'post',
			data: {
				filters: newFilter,
				page: undefined
			},
			queryStringArrayFormat: 'indices',
			replace: true,
			preserveScroll: true,
			// preserveUrl: true,
			// forceFormData: true,
		})
	}

	const onDateSelect = (value: Date[]) => {
		// chuyển hướng thủ công.
		router.visit(window.location.pathname, {
			method: 'post',
			data: {
				filters: {
					...filters,
					dates: value.length ? listDateToArrayString(value) : undefined,
				},
				page: undefined
			},
			preserveScroll: true,
		})
	}

	const searchLocation = (isClear = false) => {
		// chuyển hướng thủ công.
		router.visit(window.location.pathname, {
			method: 'post',
			data: {
				filters: {
					...filters,
					district: isClear ? undefined : formState.search,
				},
				page: undefined
			},
			preserveScroll: true,
		})
	}

	if (!allFilters) { return null; }

	return (
		<div className="flex flex-col gap-x-2 gap-y-3">
			<div className='flex gap-4 items-center'>
				{!!formState.search && <XMarkIcon width={36} height={36} className='hover:rotate-12 hover:text-orange-800' onClick={() => {
					searchLocation(true)
				}}></XMarkIcon>}
				<input type="text" value={formState.search}
					onChange={e => setFormState(old => { return { ...old, search: e.target.value } })}
					placeholder='Tìm theo địa danh'
					className='rounded-md w-full border-white md:w-96 bg-transparent placeholder:text-white text-white'
				/>
				{formState.search && <div onClick={() => { searchLocation(false) }}>
					<FingerPrintIcon width={36} height={36} className='hover:scale-110 text-gray-500 hover:text-black'></FingerPrintIcon>
				</div>}
			</div>
			{/* {allFilters.map((filter, index) => {
				return <DropdownMenu key={index} type={filter.key} label={filter.label} data={filter.data} onChange={onFilterSubmit}></DropdownMenu>
			})} */}
			<CustomTimeTable
				selected={dateSelected}
				onChange={onDateSelect}
				minDate={new Date()}
				forcus={dateSelected.length ? dateSelected[0] : undefined}
			></CustomTimeTable>
		</div>
	)
}

export default HomePage