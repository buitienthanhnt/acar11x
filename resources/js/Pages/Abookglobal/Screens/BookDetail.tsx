import { Head } from '@inertiajs/react';
import BodyLayout from '../../Ahomeglobal/Layouts/BodyLayout';
import { CurrencyDollarIcon, SparklesIcon } from '@heroicons/react/24/solid';
import SwiperImage from '../../../Components/Custom/SwiperImage';
import { Rating } from '@material-tailwind/react';
import { RoomTime } from '@/Pages/Ahomeglobal/Components';

const BookDetail = ({ book }) => {
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
							<RoomTime ></RoomTime>
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
						<p className='text-xl font-semibold text-green-800'>Giá: {book.price}.000 vnd</p>
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
							{book.book_cate.map(bookCate => <div className='bg-blue-gray-700 text-white p-1 px-2 rounded-md text-base md:text-xl font-medium shadow-md' key={bookCate.id}>{bookCate.name}</div>)}
						</div>}
					</div>

				</div>
			</div>
		</div>
	)
}

export default BookDetail;