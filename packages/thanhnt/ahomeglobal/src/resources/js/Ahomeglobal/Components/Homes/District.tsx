const dataList = [
	{
		name: 'Phú Quốc',
		number: '986 khách sạn',
		imagePath: 'http://acar11x.dev/storage/photos/demo/AA1NEgWR.jpeg',
	},
	{
		name: 'Vũng Tàu',
		number: '8 khách sạn',
		imagePath: 'http://acar11x.dev/storage/photos/tong-hop/AAHcXCl.jpeg',
	},
	{
		name: 'Đà Lạt',
		number: '1181 khách sạn',
		imagePath: 'http://acar11x.dev/storage/photos/tong-hop/AAHcEqk.jpeg',
	},
	{
		name: 'Quy Nhơn',
		number: '334 khách sạn',
		imagePath: 'http://acar11x.dev/storage/photos/tong-hop/AA1gxqUc.jpeg',
	},
	{
		name: 'Nha Trang',
		number: '1023 khách sạn',
		imagePath: 'https://cdn1.ivivu.com/images/2025/04/16/11/nhatrang_show_su3sb3_.webp',
	},
	{
		name: 'Đà Nẵng',
		number: '1364 khách sạn',
		imagePath: 'http://acar11x.dev/storage/photos/tong-hop/AAHgri9.jpeg',
	},
	{
		name: 'Phan Thiết',
		number: '222 khách sạn',
		imagePath: 'https://cdn1.ivivu.com/images/2025/04/16/13/phuquoc_show_ebkmxx_.webp',
	},
	{
		name: 'Phú Yên',
		number: '80 khách sạn',
		imagePath: 'http://acar11x.dev/storage/photos/tong-hop/AAHgkCh.jpeg',
	}
];
/**
 * Component District
 *
 * This component is used to display the list of districts on the homepage.
 * It will display 8 districts with their names and numbers of hotels.
 * The component will use the grid layout to display the list of districts.
 * The component will also use the group and hover effects to scale up the images of the districts when the user hovers over them.
 *
 * @returns {JSX.Element} The JSX element representing the District component.
 */
const District = () => {
	return (
		<div className="grid grid-cols-12 w-full grid-rows-4 gap-1 xl:gap-2">
			{dataList.slice(0, 4).map((item, index) => {
				switch (index) {
					case 0:
						return (
							<div className="col-span-8 row-span-1 " key={index + item.name}>
								<div className='relative h-full w-full overflow-hidden rounded-md group'>
									<div style={{backgroundImage: `url('${item.imagePath}')`}}
										className={`h-[124px] md:h-[200px] relative w-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
										<p row-spans-2 className="absolute left-2 md:left-10 bottom-2 md:bottom-10 ">
											<h4 className="row-span-2 font-semibold">{item.name}</h4>
											<span className="only-show-desktop invisible md:visible row-span-2">{item.number}</span>
										</p>
									</div>
								</div>
							</div>
						)
					case 1:
						return (
							<div className="col-span-4 row-span-2" key={index + item.name}>
								<div className='relative h-full w-full overflow-hidden rounded-md group'>
									<div style={{backgroundImage: `url('${item.imagePath}')`}}
										className={` relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
										<p
											row-spans-2 className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
											<h4 row-spans-2>{item.name}</h4>
											<span row-spans-2 className="only-show-desktop invisible md:visible">{item.number}</span>
										</p>
									</div>
								</div>
							</div>

						)
					default:
						return (
							<div className="col-span-4 row-span-1" key={index + item.name}>
								<div className='relative h-full w-full overflow-hidden rounded-md group'>
									<div style={{backgroundImage: `url('${item.imagePath}')`}}
										className={`relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
										<p
											row-spans-2 className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
											<h4 row-spans-2>{item.name}</h4>
											<span row-spans-2 className="only-show-desktop invisible md:visible">{item.number}</span>
										</p>
									</div>
								</div>
							</div>
						)
				}
			})
			}

			{dataList.slice(4, 8).map((item, index) => {
				switch (index) {
					case 0:
						return (
							<div className="col-span-4 row-span-2" key={index}>
								<div className='relative h-full w-full overflow-hidden rounded-md group'>
									<div style={{backgroundImage: `url('${item.imagePath}')`}}
										className={`relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
										<p
											row-spans-2 className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
											<h4 row-spans-2>{item.name}</h4>
											<span row-spans-2 className="only-show-desktop invisible md:visible">{item.number}</span>
										</p>
									</div>
								</div>
							</div>
						)
					case 1:
						return (
							<div className="col-span-8 row-span-1" key={index}>
								<div className='relative h-full w-full overflow-hidden rounded-md group'>
									<div style={{backgroundImage: `url('${item.imagePath}')`}}
										className={`relative w-full h-[124px] md:h-[200px] bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
										<p
											row-spans-2 className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
											<h4 row-spans-2>{item.name}</h4>
											<span row-spans-2 className="only-show-desktop invisible md:visible">{item.number}</span>
										</p>
									</div>
								</div>
							</div>
						)
					default:
						return (
							<div className="col-span-4 row-span-1" key={index}>
								<div className='relative h-full w-full overflow-hidden rounded-md group'>
									<div style={{backgroundImage: `url('${item.imagePath}')`}}
										className={`relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
										<p
											row-spans-2 className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
											<h4 row-spans-2>{item.name}</h4>
											<span row-spans-2 className="only-show-desktop invisible md:visible">{item.number}</span>
										</p>
									</div>
								</div>
							</div>
						)
				}
			})
			}
		</div>
	)
}

export default District;