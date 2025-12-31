
import { FunctionComponent, useCallback, useEffect, } from 'react';
import { HomeDetail as HomeDetailType } from '../types/Home.d';
import { RoomDetail, } from '../types/Room';
import { Head, router, useRemember } from '@inertiajs/react';
import { MapPinIcon, SparklesIcon } from '@heroicons/react/24/solid';
import Urls from '../netWork/Urls';
import { usePageMessage, useRoomOrders } from '../hooks';
import { RoomItem, RoomTime } from '../Components/Room';
import { Rating } from "@material-tailwind/react";
import FlashMessage from '../Components/FlashMessage';
import Location from '../Components/Location';
import useMode from '../hooks/useMode';
import { isDateInRange, listDateToArrayString } from '../Helper/DateTimeHelper';
import BodyLayout from '../Layouts/BodyLayout';
import SwiperImage from '@/Components/Custom/SwiperImage';

type Props = {
	homeDetail: HomeDetailType,
	roomSelected: RoomDetail,
	selectedDates: string[],
}
const HomeDetail: FunctionComponent<Props> = ({ homeDetail, selectedDates, roomSelected }) => {
	const messages = usePageMessage();
	const booked = useRoomOrders();
	const { isDateRangeMode } = useMode();
	const bookedDate = booked?.booked_dates || [];

	const homeDisable = homeDetail.order_times.map(t => {
		return t?.room_ids.length === homeDetail?.rooms.length ? t.date : undefined;
	}).filter(item => item !== undefined);

	const [dateSelected, setDateSelected] = useRemember<Date[]>(selectedDates.map(s => {
		return (bookedDate.includes(s) || homeDisable.includes(s)) ? undefined : new Date(s);
	}).filter(function (element) { return element !== undefined; }), 'Ahomeglobal/HomeDetail');

	/**
	 * check has disable date list in range
	 */
	const checkDisableDate = useCallback((dates: Date[] | string[]): boolean => {
		if (isDateRangeMode) {
			for (let index = 0; index < homeDisable.length; index++) {
				if (isDateInRange(
					new Date(homeDisable[index]),
					[new Date(dates[0]), new Date(dates[dates.length - 1])])
				) {
					return true;
				}
			}
			for (let index = 0; index < bookedDate.length; index++) {
				if (isDateInRange(
					new Date(bookedDate[index]),
					[new Date(dates[0]), new Date(dates[dates.length - 1])]
				)) {
					return true;
				}
			}
		}
		return false;
	}, [homeDisable, bookedDate, isDateRangeMode])

	useEffect(() => {
		/**
		 * clear selected date if has disable date in range
		 */
		if (checkDisableDate(selectedDates)) {
			setDateSelected([]);
		}
		return;
	}, [selectedDates])

	const onChangeDate = useCallback((dates: Date[]) => {
		/**
		 * stop if has date in range disable or booked
		 * optimate later.
		 */
		if (checkDisableDate(dates)) {
			return;
		}
		setDateSelected(dates);
	}, [checkDisableDate])

	const onCheckout = useCallback(() => {
		router.post(Urls.checkout, {
			dateSelected: listDateToArrayString(dateSelected),
			home: homeDetail.id,
			room: roomSelected?.id,
		})
	}, [dateSelected, homeDetail, roomSelected])

	if (!homeDetail) {
		return null;
	}

	return (
		<BodyLayout>
			<Head>
				<title>{homeDetail.name}</title>
			</Head>
			<div className='space-y-1 my-1 '>
				<HomeInfo homeDetail={homeDetail}></HomeInfo>
				{/* @ts-ignore */}
				<FlashMessage message={messages}></FlashMessage>
				{homeDetail.rooms.length ?
					<div className='space-y-2 grid grid-cols-1 lg:grid-cols-5 gap-x-1 p-1 md:p-2 rounded-md'>
						<div className='col-span-2 rounded-md space-y-2'>
							<p className='text-xl font-semibold '>Danh sách phòng:</p>
							<div className='flex flex-col gap-y-2'>
								{homeDetail.rooms.map(room => <RoomItem room={room} key={room.id.toString()} dateSelected={dateSelected}></RoomItem>)}
							</div>
						</div>
						<div className='col-span-3'>
							<RoomTime allDisable={homeDisable} dateSelected={dateSelected} setDateSelected={onChangeDate} onCheckout={onCheckout}></RoomTime>
						</div>
					</div> : (
						<div className='bg-white flex justify-center items-center rounded-md p-1 lg:p-4'>
							<p className='font-semibold text-xl text-red-500 italic'>Không có lựa chọn khả dụng!</p>
						</div>
					)
				}
			</div>
		</BodyLayout>
	);
}

const HomeInfo = ({ homeDetail }: { homeDetail: HomeDetailType }) => {
	const rate = homeDetail?.attr.find(i => i.key === 'rate');
	const location = homeDetail?.attr.find(i => i.key === 'location');

	return (
		<div className='space-y-1'>
			<p className='text-lg md:text-xl font-bold text-blue-gray-800'>Nơi lưu trú: {homeDetail.name}</p>
			<div className='flex space-x-1'>
				<MapPinIcon className="size-5 text-gray-800"></MapPinIcon>
				<p className='text-base font-semibold '>Địa chỉ: {homeDetail.district}</p>
			</div>
			<div className='grid grid-cols-1 md:grid-cols-3 lg:grid-cols-2 rounded-md gap-x-2'>
				<div className='md:col-span-2 lg:col-span-1'>
					<SwiperImage images={homeDetail.gallery}></SwiperImage>
				</div>
				<div className='flex flex-col gap-y-2 p-1 md:p-2 rounded-md col-span-1'>
					<div className='flex space-x-1'>
						<SparklesIcon className="size-5 text-gray-800"></SparklesIcon>
						<p className='text-sm md:text-base font-semibold text-gray-800'>Mô tả: {homeDetail.description}</p>
					</div>
					{
						// @ts-ignore
						rate && <Rating value={Number(rate.value as unknown as number > 5 ? 5 : rate.value)} placeholder={'rate'}
							onResize={undefined}
							onResizeCapture={undefined}
							readonly
						/>
					}
					{location &&
						<Location
							style='h-full w-full flex-1 lg:h-40 border p-1'
							url={location.value}></Location>
					}
				</div>
			</div>
		</div>
	)
}

export default HomeDetail;