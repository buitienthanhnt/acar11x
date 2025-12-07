
import { FunctionComponent, useCallback, useState } from 'react';
import { HomeDetail as HomeDetailType } from '../types/Home.d';
import { RoomDetail, RoomItem as RoomItemType } from '../types/Room';
import { router } from '@inertiajs/react';
import { useRoomOrders } from '../hooks/useRoomOrders';
import { formatIsoStringToLocal } from '../Helper/DateTimeHelper';
import { CustomTimeTable } from '../Components/CustomCalenda';
import { Button } from '@material-tailwind/react';
import { XMarkIcon } from '@heroicons/react/24/solid';

const RoomItem = ({ room }: { room: RoomItemType }) => {
	const booked = useRoomOrders();
	const onClickRoom = (room: RoomItemType)=>{
		const urlParams = new URLSearchParams(window.location.search);
		if (urlParams.get('room') === room.id.toString()) {
			router.visit(window.location.pathname, {
				method: 'get',
				only: ['roomSelected'],
			});
		}else{
			console.log(window.location.pathname, room.id);
			
			router.visit(window.location.pathname, {
				method: 'get',
				data: {room: room.id},
				only: ['roomSelected'],
			})
		}
	}
	return (
		<p className={`p-4 py-2 bg-deep-purple-300 rounded-md ${booked?.id === room.id ? 'bg-green-400' : ''}`} onClick={()=>{
			onClickRoom(room);
		}}>
			<p className='font-semibold'>
				Room: {room.title}
			</p>
		</p>
	)
}

const RoomTime = () => {
	const booked = useRoomOrders();
	const bookedDate = booked?.orders.map(order => {
		return order.selected_time.map(d => formatIsoStringToLocal(d))
	}).flat().filter((value, index, self) => { return self.indexOf(value) === index; }) || [];

	const [dateSelected, setDateSelected] = useState<Date[]>([]);

	const onSubmitOrder = useCallback(() => {
		const selectedValues = dateSelected.map(d => [d.getFullYear(), d.getMonth() + 1, d.getDate()].join('-'));
		router.visit(window.location.href, {
			method: 'post',
			data: {
				values: selectedValues,
			},
			except: ['homeDetail'],

		})

	}, [dateSelected])

	return (
		<div className='space-y-2 grid grid-col-1 md:grid-cols-3 gap-1 lg:gap-2'>
			<div className='col-span-1 flex flex-col space-y-1'>
				<h3>content of time table calende select</h3>
				<span className='text-xl font-semibold'>selected room: {booked?.title || 'Random room'}</span>
				<div className='grid grid-col-2 lg:grid-cols-3 xl:grid-col-4 gap-1 md:gap-2'>
					{bookedDate.map((order, index) => {
						return (
							<div key={index} className='p-1 bg-red-200 rounded-md flex justify-center items-center content-center'>
								<span className='text-black font-semibold'>{order}</span>
							</div>
						)
					})}
				</div>

				{!!dateSelected.length && <div className='flex justify-end'>
					<span className='bg-orange-400 rounded-full p-2' onClick={() => { setDateSelected([]); }}><XMarkIcon className='size-6 text-white font-extrabold'></XMarkIcon></span>
				</div>}
				<div className='grid grid-col-2 lg:grid-cols-3 xl:grid-col-4 gap-1 md:gap-2'>
					{dateSelected.map((date, index) => {
						return (
							<div key={index} className='p-1 bg-blue-400 rounded-md flex justify-center items-center content-center'>
								<span className='text-black font-semibold'>{date.getFullYear()}-{date.getMonth() + 1}-{date.getDate()}</span>
							</div>
						)
					})}
				</div>
				<Button placeholder={'view selected'} onClick={onSubmitOrder}>
					<span>Order the selected</span>
				</Button>
			</div>
			<div className='space-y-1 md:col-span-2'>
				<CustomTimeTable
					selected={dateSelected}
					onChange={setDateSelected}
					minDate={new Date()}
					// maxDate={new Date(2025, 11, 19)}
					disable={bookedDate.map(s => new Date(s))}
				></CustomTimeTable>
			</div>
		</div>
	)
}

type Props = {
	homeDetail: HomeDetailType,
	roomSelected: RoomDetail,
}
const HomeDetail: FunctionComponent<Props> = ({ homeDetail, roomSelected }) => {

	return (
		<div className='p-4 bg-blue-gray-100 rounded-md min-h-screen space-y-2'>
			<p className='text-2xl font-bold text-black'>Hotel: {homeDetail.name}</p>
			<p className='text-xl font-semibold text-purple-500'>List rooms of the hotel:</p>
			<div className='grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>
				{homeDetail.rooms.map(room => <RoomItem room={room} key={room.id.toString()}></RoomItem>)}
			</div>
			<RoomTime></RoomTime>
		</div>
	);
}

export default HomeDetail;