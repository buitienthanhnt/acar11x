import { Head, router, useRemember } from '@inertiajs/react';
import { FingerPrintIcon, XMarkIcon } from '@heroicons/react/24/solid';
import BaseLayout from '../Layouts/BaseLayout';
import { FooterWithLogo, NavbarDark } from '../Layouts/Components';
import BookGridItem from '../Components/BookGridItem';
import { FlashMessage, Title, Paginate, HorizonSlick, GalleryList } from '@/Pages/Amuaglobal/Components';
import HomeSpeed from '@/Pages/Amuaglobal/Components/Layout/HomeSpeed';
import { useMessage } from '@/Pages/Amuaglobal/hooks';
import data from '../data/centercate';
import Urls from '../network/Urls';
import { sprintf } from 'sprintf-js';
import { PaginateInterface } from '@/Pages/Amuaglobal/types/Paginate';
import { BookItemType } from '../Types/BookType';

/**
 * 
 * https://www.npmjs.com/package/react-multi-carousel 
 */

const HomePage = ({ filters, books }: {filters: any, books: PaginateInterface}) => {
	const { error, success } = useMessage();

	return (
		<BaseLayout>
			<Head title="Trang chủ">
				<style>
					{``}
				</style>
			</Head>
			<div className="flex flex-col sm:justify-center sm:pt-0 min-h-screen mx-auto 
						 dark:bg-gray-300 ">
				<NavbarDark navStyles={'rounded-none max-w-full'}></NavbarDark>
				<div className="relative h-0 flex justify-end overflow-y-visible">
					{(error || success) && <FlashMessage message={error || success} type={error ? 'error' : 'success'}
						className="absolute top-4 flex right-0 w-auto ">
					</FlashMessage>}
				</div>

				<div className="w-full mx-auto bg-gradient-to-r from-blue-gray-900 to-blue-gray-800">
					<div className="min-h-36 md:min-h-[480px] p-1 md:p-4 bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center">
						<div className="grid lg:grid-cols-6 xl:grid-cols-7 lg:space-x-4 space-y-2 lg:space-y-0">
							<div className="col-span-1 lg:col-span-2">
								<BookFilter filters={filters}></BookFilter>
							</div>
							<div className='lg:col-spans-4 xl:col-span-5 flex flex-col w-full space-y-1'>
								<p className='text-2xl font-semibold text-white invisible md:visible'>Trải nghiệm kỳ nghỉ tuyệt vời</p>
								<h3 className='text-white font-semibold text-xl invisible md:visible'>
									Combo khách sạn - vé máy bay - đưa đón sân bay giá tốt nhất
								</h3>
							</div>
						</div>
					</div>
				</div>

				<div className="flex flex-col flex-1 w-full p-1 mx-auto md:container">
					<div className="space-y-4">
						<div className="col-span-1 lg:col-span-3 flex flex-col gap-y-1 md:gap-y-2 p-1 md:p-0 lg:space-x-4 md:py-2 py-1 space-y-2 lg:space-y-0">
							<Title title='Nổi bật'></Title>
							<div className='grid md:grid-cols-3 lg:grid-cols-4 gap-1 md:gap-2 lg:gap-3 w-full'>
								{books?.data.map(book => <BookGridItem book={book} key={book.id.toString()}></BookGridItem>)}
							</div>
							<Paginate pageSize={books.last_page} currentPage={books.current_page}
								mergeData={{ filters: filters || undefined }} linkProps={{
									method: 'get',
									preserveScroll: true,
									prefetch: ['hover',], // prefecth must be use in GET request only
								}}></Paginate>
						</div>
						<HorizonSlick items={books?.data} onClick={(item) => {
							router.get(item.url)
						}}></HorizonSlick>
						<div className='space-y-4'>
							<Title title='Tổng hợp'></Title>
							<GalleryList data={data}></GalleryList>
						</div>
					</div>
					<HomeSpeed></HomeSpeed>
				</div>
				<FooterWithLogo></FooterWithLogo>
			</div>
		</BaseLayout>
	);
}

const BookFilter = ({ filters }: any) => {

	const [formState, setFormState] = useRemember({
		search: filters?.district || '',
	}, 'page.search')

	const searchLocation = (isClear = false) => {
		// chuyển hướng thủ công.
		router.visit(window.location.pathname, {
			method: 'get',
			data: {
				filters: {
					...filters,
					name: isClear ? undefined : formState.search,
				},
				page: undefined
			},
			preserveScroll: true,
		})
	}

	return (
		<div className="flex flex-col gap-x-2 gap-y-3">
			<div className='flex gap-4 items-center'>
				{!!formState.search && <XMarkIcon width={36} height={36} className='hover:rotate-12 hover:text-orange-800' onClick={() => {
					searchLocation(true)
				}}></XMarkIcon>}
				<input type="text" value={formState.search}
					onChange={e => setFormState(old => { return { ...old, search: e.target.value } })}
					placeholder='Tìm theo địa danh'
					className='rounded-md w-full md:w-96 bg-transparent'
				/>
				{formState.search && <div onClick={() => { searchLocation(false) }}>
					<FingerPrintIcon width={36} height={36} className='hover:scale-110 text-gray-500 hover:text-black'></FingerPrintIcon>
				</div>}
			</div>
		</div>
	)
}


export default HomePage;