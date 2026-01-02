import { Head } from "@inertiajs/react";
import { OrderDetailInterface } from "../types/Order";
import { FunctionComponent } from "react";
import BodyLayout from "../Layouts/BodyLayout";

type Props = {
	order: OrderDetailInterface
};
const CheckoutSuccess: FunctionComponent<Props> = ({ order }) => {
	return (
		<BodyLayout>
			<Head>
				<title>checkout success</title>
			</Head>
			<div className="flex-1 rounded-md p-4 ">
				<span className="text-xl text-green-600">
					Trân trọng cảm ơn quý khách hàng đã đặt phòng!
				</span>
				<p className="text-md font-semibold">Thông tin hóa đơn: {order.increment_id}</p>
				<div className="flex md:flex-row md:justify-between md:gap-x-2 flex-col gap-y-2 mt-4">
					<div className="rounded-md border border-black p-2 flex-1">
						<p className="text-md font-semibold text-gray-800">Thông tin khách hàng:</p>
						<p className="text-md font-semibold">Họ và tên: {order.detail.name}</p>
						<p className="text-md font-semibold">Email: {order.detail.email}</p>
						<p className="text-md font-semibold">Số điện thoại: {order.detail.phone}</p>
					</div>

					<div className="rounded-md border border-black p-2 flex-1">
						<p className="text-md font-semibold text-gray-800">Khách sạn: {order.home.name}</p>
						<p className="text-md font-semibold ">Địa chỉ: {order.home.district}</p>
						<p className="text-md font-semibold ">Liên hệ: 0702032201</p>
					</div>

					<div className="rounded-md border border-black p-2 flex-1">
						<p className="text-md font-semibold text-gray-800">Thông tin phòng: {order.room.title}</p>
						<p className="text-md font-semibold text-gray-800">Thời gian lưu trú:</p>
						<div className="flex flex-wrap gap-2 mt-2">
							{order.selected_time.map(time => <p className="text-md font-semibold p-1 px-2 bg-gray-500 rounded-md" key={time}>{time}</p>)}
						</div>
					</div>
				</div>
				<div className="mt-4">
					<p className="text-md font-semibold">Quý khách có thắc mắc có thể liên hệ với khách sạn qua sdt: 0702032201</p>
				</div>
			</div>
		</BodyLayout>

	)
}

export default CheckoutSuccess;