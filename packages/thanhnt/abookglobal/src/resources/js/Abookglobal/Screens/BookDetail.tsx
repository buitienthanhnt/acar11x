import { useCallback, useEffect } from 'react';
import { Deferred, Head, Link, router, useRemember } from '@inertiajs/react';
import { Rating } from '@material-tailwind/react';
import { CurrencyDollarIcon, SparklesIcon } from '@heroicons/react/24/solid';
import BodyLayout from '../Layouts/BodyLayout';
import Urls from '../network/Urls';
import BookCalenda from '../Components/BookCalenda';
import { sprintf } from 'sprintf-js';
import SwiperImage from '@/Components/Custom/SwiperImage';
import { BookItemType } from '../Types/BookType';
import {usePageProps, useMode} from '@/Pages/Amuaglobal/hooks';
import { formatCurrency, isDateInRange, listDateToArrayString, dateToServerString } from "@/Pages/Amuaglobal/Helper";

const BookDetail = ({ book, bookedTimes }) => {
	const { isDateRangeMode } = useMode();
	const [dateSelected, setDateSelected] = useRemember<Date[]>([], 'abookglobal/bookDetail');

	const bookedDate = Object.keys(bookedTimes).map(dateStr => {
		if (bookedTimes[dateStr] >= book.qty) {
			return dateStr;
		}
	}).filter(Boolean);

	const checkDisableDate = useCallback((dates: Date[] | string[]): boolean => {
		if (isDateRangeMode) {
			for (let index = 0; index < bookedDate.length; index++) {
				if (isDateInRange(
					new Date(bookedDate[index]),
					[new Date(dates[0]), new Date(dates[dates.length - 1])])
				) {
					return true;
				}
			}
		} else {
			for (let index = 0; index < dates.length; index++) {
				if (bookedDate.includes(dateToServerString(new Date(dates[index])))) {
					return true;
				}
			}
		}
		return false;
	}, [bookedDate, isDateRangeMode])

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
		/**
		 * preserveState: để tránh lưu trạng thái của trang hiện tại khi chuyển trang.
		 * nếu để true sẽ lưu trạng thái làm không render lại phần flashMessage do vị trí không đổi và trạng thái lại không thay đổi.
		 * nhưng khi để false thì trang sẽ render lại hoàn toàn và sẽ mất các trạng thái đã lưu trữ trước đó ví dụ như dateSelected.
		 * 
		 */
		router.post(Urls.checkout, {
			book_id: book.id,
			dateSelected: listDateToArrayString(dateSelected),
		}, { preserveState: false, });
	}, [dateSelected, book.id])

	useEffect(() => {
		/**
		 * clear selected date if has disable date in range
		 */
		if (checkDisableDate(dateSelected)) {
			setDateSelected([]);
		}
		return;
	}, [dateSelected, checkDisableDate])

	if (!book) {
		return null;
	}

	return (
		<BodyLayout>
			<Head>
				<title>{book.name}</title>
			</Head>
			<div className='space-y-1 my-1 '>
				<BookInfo book={book}></BookInfo>
				{book ?
					<div className='space-y-2 grid grid-cols-1 lg:grid-cols-5 gap-x-1 p-1 md:p-2 rounded-md'>
						<div className='col-span-3'>
							<BookCalenda setDateSelected={onChangeDate} dateSelected={dateSelected} onCheckout={onCheckout} bookedDate={bookedDate}></BookCalenda>
						</div>
					</div> : (
						<div className='bg-white flex justify-center items-center rounded-md p-1 lg:p-4'>
							<p className='font-semibold text-xl text-red-500 italic'>Không có lựa chọn khả dụng!</p>
						</div>
					)
				}
				<BookRelated></BookRelated>
			</div>
		</BodyLayout>
	);
}

const BookRelated = () => {
	const { relatedBooks } = usePageProps() as unknown as { relatedBooks: BookItemType[] };

	return (
		<Deferred fallback={<div>Loading related books...</div>} data={'relatedBooks'}>
			<div className='space-y-2 p-1 md:p-2'>
				<p className='text-xl font-bold text-blue-gray-800'>Danh sách liên quan</p>
				{relatedBooks && <div className='grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-2' >
					{relatedBooks.map(book => {
						return (
							<Link key={book.id} href={sprintf(Urls.bookDetail, [book.id])} className='flex-1 flex flex-col border border-gray-400 rounded-md space-y-1 justify-between'>
								<img src={book.image_path} alt="image" className='w-full h-auto rounded-t-md object-contain' />
								<div className='p-1 space-y-[2px]'>
									<p className='font-semibold text-md '>{book.name}</p>
									<p className='text-green-700 text-sm'>{book.description}</p>
									<p className='font-bold text-purple-600'>{formatCurrency(book.price)}</p>
									{
										// @ts-ignore
										book?.rate && <Rating value={Number(book.rate as unknown as number > 5 ? 5 : book.rate)} placeholder={'rate'}
											onResize={undefined}
											onResizeCapture={undefined}
											readonly
										/>
									}
								</div>
							</Link>
						)
					})}
				</div>}
			</div>
		</Deferred>
	)
}

const BookInfo = ({ book }) => {
	return (
		<div className='space-y-1 py-4'>
			<div className='grid grid-cols-1 md:grid-cols-3 lg:grid-cols-2 rounded-md gap-x-2'>
				<div className='md:col-span-2 lg:col-span-1'>
					<SwiperImage images={book.gallery}></SwiperImage>
				</div>
				<div className='flex flex-col gap-y-2 p-1 md:p-2 rounded-md col-span-1'>
					<p className='text-lg md:text-3xl font-bold text-blue-gray-800'>Tác phẩm: {book.name}</p>
					<div className='flex space-x-1 items-center'>
						<SparklesIcon className="size-5 text-gray-800"></SparklesIcon>
						<p className='text-sm md:text-xl font-semibold text-gray-800'>Mô tả: {book.description}</p>
					</div>
					<div className='flex space-x-1 items-center'>
						<CurrencyDollarIcon className="size-5 text-gray-800"></CurrencyDollarIcon>
						<p className='text-xl font-semibold text-green-800'>Giá: {formatCurrency(book.price)}</p>
					</div>
					{
						// @ts-ignore
						book?.rate && <Rating value={Number(book.rate as unknown as number > 5 ? 5 : book.rate)} placeholder={'rate'}
							onResize={undefined}
							onResizeCapture={undefined}
							readonly
						/>
					}
					<div className='space-y-1'>
						<p className='text-xl text-gray-900 font-semibold'>Chủ đề:</p>
						{book?.book_cate && <div className='flex flex-wrap gap-2'>
							{book.book_cate.map(bookCate => <Link href={sprintf(Urls.bookCate, [bookCate.id])}
								className='border-gray-600 border px-2 rounded-md text-base md:text-lg font-medium md:font-semibold shadow-md'
								key={bookCate.id}>{bookCate.name}
							</Link>
							)}
						</div>}
					</div>

				</div>
			</div>
		</div>
	)
}

export default BookDetail;