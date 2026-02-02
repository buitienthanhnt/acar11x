import usePageProps from "@/Pages/Ahomeglobal/hooks/usePageProps";
import BodyLayout from '@/Pages/Ahomeglobal/Layouts/BodyLayout';
import { Head, Link } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../network/Urls";
import useMode from "@/hook/useMode";
import { formatCurrency } from "@/Pages/Ahomeglobal/Helper";

const CheckoutSuccess = () => {
	const { order: { book, selected_time, date_from, date_to, shipping_address, customer_info, payment_method, shipping_method, total_price, qty }, shipping_method: shippingMethodList } = usePageProps();
	const shippingMethodSelected = shippingMethodList.find(item => item.key === shipping_method);
	return (
		<BodyLayout contentClass="md:max-w-none">
			<Head>
				<title>đơn hàng</title>
			</Head>
			<div className="flex-1 bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center">
				<div className="w-full p-1 md:p-2 space-y-2 container mx-auto">
					<h1 className='text-2xl font-semibold text-blue-400'>Đặt hàng thành công!</h1>
					<div className="grid grid-cols-2 gap-2">
						<div className="bg-gray-200 p-1 flex space-x-2">
							<img src={book.image_path} alt="image" className='w-48 h-48 aspect-square md:max-h-60 rounded-md col-span-1 object-cover' />
							<Link href={sprintf(Urls.bookDetail, [book.id])}>
								<p>{book.name}</p>
								<p>{book.description}</p>
								<p>{formatCurrency(book.price)}</p>
							</Link>
						</div>
						<div className="bg-gray-200 p-1">
							<p className="font-semibold">Thời gian lựa chọn:</p>
							<TimeInfo date_from={date_from} date_to={date_to} selected_time={selected_time} />
						</div>
					</div>
					<div className="grid grid-cols-3 gap-2">
						<div className="bg-gray-200 p-1">
							<h3 className="font-semibold">Thông tin khách hàng:</h3>
							<p>Tên: {customer_info.name}</p>
							<p>Số điện thoại: {customer_info.phone}</p>
							<p>email: {customer_info.email}</p>
						</div>
						<div className="bg-gray-200 p-1">
							<p className="font-semibold">Phương thức giao hàng: {shippingMethodSelected.name}</p>
							<h3>Địa chỉ giao hàng:</h3>
							<p>Tên: {shipping_address.name}</p>
							<p>Số điện thoại: {shipping_address.phone}</p>
							<p>Địa chỉ: {shipping_address.location}</p>
						</div>
						<div className="bg-gray-200 p-1">
							<p className="font-semibold">Phương thức thanht toán: {payment_method}</p>
							<p className="font-semibold">Tổng giá sản phẩm(Giá x thời gian x số lượng): {formatCurrency(book.price * selected_time.length * qty)}</p>
							<p className="font-semibold">Phí vận chuyển: {formatCurrency(shippingMethodList.find(item => item.key === shipping_method)?.shipping_cost)}</p>
							<p className="font-semibold">Tổng giá: {formatCurrency(total_price)}</p>
						</div>
					</div>
				</div>
			</div>
		</BodyLayout>
	)
}

const TimeInfo = ({ date_from, date_to, selected_time }: { date_from: string, date_to: string, selected_time: string[] }) => {
	const { isDateRangMode } = useMode();

	if (selected_time.length === 1) {
		return (
			<div>
				Trong ngày: {date_from}
			</div>
		)
	}

	if (isDateRangMode) {
		return (
			<div>
				<p>Từ ngày: {date_from}</p>
				<p>Đến ngày: {date_to}</p>
			</div>
		)
	}

	return (
		<div className="flex flex-wrap gap-2">
			{selected_time.map((item, index) => <p key={index} className="bg-gray-400 p-1 px-2 rounded-md font-semibold">{item}</p>)}
		</div>
	)
}

export default CheckoutSuccess;