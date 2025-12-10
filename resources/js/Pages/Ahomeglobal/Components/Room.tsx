
import { useCallback, useState } from 'react';
import { RoomItem as RoomItemType } from '../types/Room';
import { router } from '@inertiajs/react';
import { CustomTimeTable } from '../Components/CustomCalenda';
import { Button, } from '@material-tailwind/react';
import { XMarkIcon, } from '@heroicons/react/24/solid';
import { usePageMessage, useRoomOrders } from '../hooks';

const RoomItem = ({ room }: { room: RoomItemType }) => {
	const booked = useRoomOrders();
	const messages = usePageMessage();

	const onClickRoom = (room: RoomItemType) => {
		const urlParams = new URLSearchParams(window.location.search);
		if (urlParams.get('room') === room.id.toString()) {
			router.visit(window.location.pathname, {
				method: 'get',
			});
		} else {
			router.visit(window.location.pathname, {
				method: 'get',
				data: { room: room.id },
				only: messages ? [] : ['roomSelected'], // clear app props if has flash mesasge.
			})
		}
	}
	return (
		<p className={`p-4 py-2 bg-deep-purple-300 rounded-md ${booked?.id === room.id ? 'bg-green-400' : ''}`} onClick={() => {
			onClickRoom(room);
		}}>
			<p className='font-semibold'>
				Room: {room.title}
			</p>
		</p>
	)
}

const RoomTime = ({ allDisable }: { allDisable?: string[] }) => {
	const booked = useRoomOrders();
	const bookedDate = booked?.booked_dates || [];

	const [dateSelected, setDateSelected] = useState<Date[]>([]);

	const onSubmitOrder = useCallback(() => {
		const selectedValues = dateSelected.map(d => [
			d.getFullYear(),
			d.getMonth() + 1 >= 10 ? d.getMonth() + 1 : '0' + (d.getMonth() + 1).toString(),
			d.getDate() < 10 ? '0' + d.getDate().toString() : d.getDate(),
		].join('-'));

		router.visit(window.location.href, {
			method: 'post',
			data: {
				values: selectedValues,
			},
		})

	}, [dateSelected])

	return (
		<div className='space-y-2 grid grid-col-1 md:grid-cols-3 gap-1 lg:gap-2'>
			<div>
				<span className='text-xl font-semibold'>Selected room: {booked?.title || 'Random room'}</span>
				<div className='col-span-1 flex flex-col space-y-1'>
					{!!dateSelected.length && <div className='flex justify-between items-center'>
						<span className='text-xl font-semibold'>Your selected:</span>
						<span className='bg-orange-400 rounded-full p-2' onClick={() => { setDateSelected([]); }}><XMarkIcon className='size-6 text-white font-extrabold'></XMarkIcon></span>
					</div>}
					<div className='grid grid-cols-3 xl:grid-col-4 gap-1 md:gap-2'>
						{dateSelected.map((date, index) => {
							return (
								<div key={index} className='p-1 bg-blue-400 rounded-md flex justify-center items-center content-center'>
									<span className='text-black font-semibold'>{date.getFullYear()}-{date.getMonth() + 1}-{date.getDate()}</span>
								</div>
							)
						})}
					</div>
					{!!dateSelected.length && <Button placeholder={'view selected'} onClick={onSubmitOrder}>
						<span>Order the selected</span>
					</Button>}
				</div>
			</div>
			<div className='space-y-1 md:col-span-2'>
				<CustomTimeTable
					selected={dateSelected}
					onChange={setDateSelected}
					minDate={new Date()}
					// maxDate={new Date(2025, 11, 19)}
					disable={[...bookedDate, ...allDisable].map(s => new Date(s))}
				></CustomTimeTable>
			</div>

		</div>
	)
}

export { RoomItem, RoomTime };