import { Head, Link } from '@inertiajs/react';
import BodyLayout from '../../Ahomeglobal/Layouts/BodyLayout';
import { Paginate } from '@/Pages/Ahomeglobal/Components';
import { sprintf } from 'sprintf-js';
import Urls from '../network/Urls';
import { formatCurrency } from '@/Pages/Ahomeglobal/Helper';

const BookByCategory = ({ category, bookPaginate }) => {
	return (
		<BodyLayout>
			<Head>
				<title>{category.name}</title>
			</Head>
			<div className='space-y-4 flex-1'>
				<CategoryInfo category={category}></CategoryInfo>
				<BookPaginate bookPaginate={bookPaginate}></BookPaginate>
			</div>
		</BodyLayout>
	);
}

const CategoryInfo = ({ category }) => {
	return (
		<div className='flex gap-2 md:gap-4'>
			<div>
				<img src={category.image_path} alt="image" className='w-32 h-32 rounded-full object-cover' />
			</div>
			<div>
				<p className='text-base lg:text-xl text-orange-800 font-bold'>danh muc: {category.name}</p>
				<p>{category.description}</p>
			</div>
		</div>
	)
}

const BookPaginate = ({ bookPaginate }) => {

	return (
		<div>
			<div className='p-1 md:p-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-1 md:gap-2'>
				{bookPaginate.data.map((book) => {
					return (
						<Link href={sprintf(Urls.bookDetail, [book.id])}
							className='grid grid-cols-2 md:grid-cols-1 bg-gray-100 space-y-1 gap-1 hover:border-1 hover:border-gray-400 hover:shadow-md rounded-md' key={book.id}>
							<img src={book.image_path} alt="image" className='w-full h-auto md:h-60 rounded-t-md col-span-1 object-cover' />
							<div className='space-y-1 col-span-1 p-1'>
								<p className='font-semibold text-base'>{book.name}</p>
								<p className='text-sm font-semibold'>{book.description}</p>
								<p className='font-medium text-blue-500'>{formatCurrency(book.price)}</p>

							</div>
						</Link>
					);
				})}
			</div>
			<Paginate pageSize={bookPaginate.last_page} currentPage={bookPaginate.current_page}></Paginate>
		</div>
	);
}

export default BookByCategory;