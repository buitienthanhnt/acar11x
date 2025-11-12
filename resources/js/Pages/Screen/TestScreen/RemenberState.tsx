
import InputError from '@/Components/InputError';
import SingleLayout from '@/Layouts/BuildLayout/SingleLayout';
import { ChevronDoubleRightIcon, XMarkIcon } from '@heroicons/react/24/solid';
import { ArrowDownCircleIcon, FingerPrintIcon } from '@heroicons/react/24/solid';
import { Head, Link, router, usePage, useRemember } from '@inertiajs/react';

/**
 *  https://kinsta.com/blog/inertia-js/
 */
const RemenberState = () => {

	return (
		<>
			<Head>
				<title>remenber search</title>
				<style>
					{`
					@keyframes fade-in {
						from { opacity: 0; }
					  }
					  
					  @keyframes fade-out {
						to { opacity: 0; }
					  }
					  
					  @keyframes slide-from-right {
						from { transform: translateX(30px); }
					  }
					  
					  @keyframes slide-to-left {
						to { transform: translateX(-30px); }
					  }
					  
					  ::view-transition-old(root) {
						animation: 90ms cubic-bezier(0.4, 0, 1, 1) both fade-out,
						  300ms cubic-bezier(0.4, 0, 0.2, 1) both slide-to-left;
					  }
					  
					  ::view-transition-new(root) {
						animation: 210ms cubic-bezier(0, 0, 0.2, 1) 90ms both fade-in,
						  300ms cubic-bezier(0.4, 0, 0.2, 1) both slide-from-right;
					  }
					`}
				</style>
			</Head>
			<div className='p-2 bg-white rounded-md'>
				<SearchElement></SearchElement>
			</div>
		</>
	)
}

RemenberState.layout = page => <SingleLayout children={page}></SingleLayout>;

export default RemenberState;

const SearchElement = () => {
	const { props: { dataSearch: { data, last_page, current_page }, errors } } = usePage() as any;
	/**
	 * reload the page
	 * clear search query, clear dataSearch value.
	 */
	const reloadPage = () => {
		router.get(window.location.pathname, undefined, {
			reset: ['dataSearch'],
		})
	}
	/**
	 * useRemember dùng khá giống useState: Ghi nhớ trạng thái của thành phần, 
	 * thường được dùng trong trường hợp giữ thông tin giá trị form khi điều hướng lại trong chính trang đó.
	 * Trong ví dụ này là để lưu thông tin tìm kiếm, theo đó khi trong Link có: preserveState thì nó sẽ lưu lại thông tin search trước đó
	 * Sau khi điều hướng tìm kiếm thì nó vẫn tồn tại giá trị: search
	 * Còn nếu không nó sẽ không giữ lại giá trị search(coi như mọi thứ đặt lại mặc định ban đầu) để dùng cho trang sau dẫn tới tải tiếp bị lỗi(to the page without preserveState Link).
	 * Cái tối ưu của nó là không cần truyền lại query value trong controller xuống view hay bắt trên đường dẫn của trang mà vẫn truy xuất được tự động trong bộ nhớ.
	 * Điều kiện hoạt động là trong yêu cầu gửi đi phải có: preserveState
	 * Dùng: preserveScroll để giữ lại trạng thái cuộn đang giữ, trong ví dụ thì nếu không có: preserveScroll sau khi tải trang tiếp nó sẽ đặt lại vị trí cuộn
	 * 		Nếu có: preserveScroll thì sẽ giữ lại vị trí cuộn hiện tại.
	 */
	const [formState, setFormState] = useRemember({
		search: '',
	}, 'page.search')

	return (
		<div className='flex flex-col gap-2'>
			<p className='text-xl font-semibold text-blue-gray-600'>Search: {formState.search}</p>
			<div className='flex gap-3 items-center'>
				{!!formState.search && <XMarkIcon width={36} height={36} className='hover:rotate-12 hover:text-orange-800' onClick={() => {
					reloadPage();
					// setFormState({search: ''})
				}}></XMarkIcon>}
				<input type="text" value={formState.search}
					onChange={e => setFormState(old => { return { ...old, search: e.target.value } })}
					placeholder='search input'
					className='rounded-md w-full md:w-96'
				/>
				<Link href={window.location.href} data={{ query: formState.search, page: undefined }} preserveState>
					<FingerPrintIcon width={36} height={36} className='hover:scale-110 text-gray-500 hover:text-black'></FingerPrintIcon>
				</Link>
			</div>
			<InputError message={errors.query} className="mt-2" />
			{
				data && <div className='space-y-2'>
					{data.map((item) => <Link href={route('detail', { alias: item.alias })} viewTransition={true} key={`search.${item.id}`}
						className='flex gap-x-2 items-baseline hover:underline'>
						<ChevronDoubleRightIcon width={16} height={16} type='solid'></ChevronDoubleRightIcon>
						<p className='text-light-blue-500 font-semibold text-xl'>{item.title}</p>
					</Link>)
					}
				</div>
			}
			{(current_page < last_page) && <Link href={window.location.href} className='flex justify-center' data={{ query: formState.search, page: current_page + 1 }} preserveState preserveScroll only={['dataSearch']}>
				<ArrowDownCircleIcon width={36} height={36}></ArrowDownCircleIcon>
			</Link>}

			{/* <Link href={window.location.href} data={{ query: formState.search }} preserveState className='p-2 text-white bg-blue-gray-800 rounded-md'>to the page with preserveState</Link> */}
			{/* <Link href={window.location.href} data={{ query: formState.search }} className='text-white bg-blue-gray-800 rounded-md p-2'>to the page without preserveState</Link> */}
			{/* <Link href={'/test/remenber-post'} className='text-white bg-blue-gray-800 rounded-md p-2' method='post'>to the post remember</Link> */}
			{/* <p className='text-white bg-blue-gray-800 rounded-md p-2' onClick={reloadPage}>reload page</p> */}
			{/* <form action={'/test/remenber-post'} method='POST' className='flex flex-col gap-2'>
					<div>
						<p>name of user:</p>
						<input type="text" name="name" placeholder='user name' />
					</div>
					<div>
						<p>email of user:</p>
						<input type="email" name="email" placeholder='user email' />
					</div>
					<input type="checkbox" name='remember'/> Remember Me
					<div>
						<button type='submit' className='text-white bg-blue-gray-800 rounded-md p-1'>submit form</button>
					</div>
				</form> */}
		</div>
	)
}