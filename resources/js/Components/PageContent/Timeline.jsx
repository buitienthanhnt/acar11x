import { useEffect, useMemo, useState } from "react";

const Timeline = ({ content }) => {
	const jsonData = JSON.parse(content.value);

	return (
		<div className="bg-gray-200 p-2 rounded-md">
			<p className="font-semibold text-xl text-green-600">{jsonData.typeTitle}: {jsonData.timeValue}</p>
			<DownTime timeValue={jsonData.timeValue}></DownTime>
		</div>
	)
}

const DownTime = ({ timeValue }) => {
	const [timeData, setTimeData] = useState();
	const [isFinish, setIsFinish] = useState(false);
	const targetTime = useMemo(() => {
		return (new Date(timeValue)).getTime();
	}, [])

	useEffect(() => {
		const timer = setInterval(() => {
			const timeNow = new Date().getTime();
			// Calculate the time remaining
			const timeLeft = targetTime - timeNow;
			// Calculate days, hours, minutes, and seconds
			const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24)); 						 // chia 24h lấy ngày lẻ(làm tròn xuống)
			const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)); // chia 24h lấy dư số giờ(<24) chia 60m lấy phút lẻ(làm tròn xuống)
			const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));         // chia 60m lấy dư phút(<60) lẻ chia 60s(làm tròn xuống)
			const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);					 // chia 60s lấy dư số giây(<60) chia 1000ms(làm tròn xuống)
			if (timeLeft < 0) {
				setIsFinish(true);
				clearInterval(timer);
				return;
			}
			setTimeData({
				days: days,
				hours: hours,
				minutes: minutes,
				seconds: seconds,
			})
		}, 1000)
	}, [targetTime])

	if (isFinish) {
		return (
			<p className="font-semibold text-2xl flex justify-center text-orange-800">
				Finish of event!
			</p>
		)
	}

	return (
		<div>
			{timeData && <p className="font-semibold text-2xl flex justify-center text-gray-800">
				Còn: {timeData.days ? `${timeData.days} Ngày` : ''} {timeData.hours ? `${timeData.hours} giờ` : ''} {timeData.minutes ? `${timeData.minutes} phút` : ''} {timeData.seconds ? `${timeData.seconds} giây` : ''}
			</p>}
		</div>
	)
}

export default Timeline;