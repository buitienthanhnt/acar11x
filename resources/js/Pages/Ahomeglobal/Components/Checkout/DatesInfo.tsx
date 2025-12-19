import { useMode } from "../../hooks";

const DatesInfo = ({ dateSelected, totalPrice }: { dateSelected: Date[], totalPrice: number }) => {
	const { isDateRangeMode } = useMode();

	if (dateSelected.length === 0) {
		return;
	}

	if (isDateRangeMode) {
		if (dateSelected.length === 1) {
			return <div className='flex flex-col gap-1 md:gap-2 p-1 md:p-2 bg-gray-400 rounded-md'>
				{dateSelected[0] &&
					<div className='text-xl font-semibold text-blue-500'>
						In Date: {dateSelected[0].getFullYear()}-{dateSelected[0].getMonth() + 1}-{dateSelected[0].getDate()}
					</div>
				}
				<div className="bg-black h-[1px] mt-2"></div>
				<p className="text-xl font-semibold">Total price: {totalPrice}.000 vnđ</p>
			</div>
		}
		return (
			<div className='flex flex-col gap-1 md:gap-2 p-1 md:p-2 bg-gray-400 rounded-md'>
				{dateSelected[0] &&
					<div className='text-xl font-semibold text-blue-500'>
						Date from: {dateSelected[0].getFullYear()}-{dateSelected[0].getMonth() + 1}-{dateSelected[0].getDate()}
					</div>
				}
				{dateSelected[dateSelected.length - 1] &&
					<div className='text-xl font-semibold text-blue-500'>
						Date to: {dateSelected[dateSelected.length - 1].getFullYear()}-{dateSelected[dateSelected.length - 1].getMonth() + 1}-{dateSelected[dateSelected.length - 1].getDate()}
					</div>
				}
				<div className="bg-black h-[1px] mt-2"></div>
				<p className="text-xl font-semibold">Total price: {totalPrice}.000 vnđ</p>
			</div>
		);
	}

	return (
		<div className="bg-gray-400 p-1 md:p-2 rounded-md">
			<h3 className="text-md md:text-lg font-semibold my-1">Selected dates:</h3>
			<div className='grid grid-cols-3 gap-1 md:gap-2'>
				{dateSelected.map((date, index) => {
					return (
						<div key={index} className='p-1 bg-blue-400 rounded-md flex justify-center items-center content-center'>
							<span className='text-black font-semibold'>{date.getFullYear()}-{date.getMonth() + 1}-{date.getDate()}</span>
						</div>
					)
				})}
			</div>
			<div className="bg-black h-[1px] mt-2"></div>
			<p className="text-xl font-semibold">Total price: {totalPrice}.000 vnđ</p>
		</div>
	);
}

export default DatesInfo;