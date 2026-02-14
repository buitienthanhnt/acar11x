import { useMode } from '../../hooks';

export const SelectedInfo = ({ dateSelected }: { dateSelected: Date[] }) => {
	const { isDateRangeMode } = useMode();
	const from = new Date(Math.min.apply(null, dateSelected));
	const to = new Date(Math.max.apply(null, dateSelected));

	if (dateSelected.length === 0) {
		return;
	}

	if (isDateRangeMode) {
		if (dateSelected.length === 1) {
			return <div className='flex flex-col gap-1 md:gap-2'>
				{dateSelected[0] &&
					<div className='text-xl font-semibold text-blue-500'>
						Trong ngày: {dateSelected[0].getFullYear()}-{dateSelected[0].getMonth() + 1}-{dateSelected[0].getDate()}
					</div>
				}
			</div>
		}
		return (
			<div className='flex flex-col gap-1 md:gap-2'>
				{dateSelected[0] &&
					<div className='text-xl font-semibold text-blue-500'>
						Từ: {from.getFullYear()}-{from.getMonth() + 1}-{from.getDate()}
					</div>
				}
				{dateSelected[dateSelected.length - 1] &&
					<div className='text-xl font-semibold text-blue-500'>
						Đến: {to.getFullYear()}-{to.getMonth() + 1}-{to.getDate()}
					</div>
				}
			</div>
		);
	}

	return (
		<div className='grid grid-cols-2 gap-1 md:gap-2'>
			{dateSelected.map((date, index) => {
				return (
					<div key={index} className='p-1 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-md flex justify-center items-center content-center'>
						<span className='text-black font-semibold'>{date.getFullYear()}-{date.getMonth() + 1}-{date.getDate()}</span>
					</div>
				)
			})}
		</div>
	);
}