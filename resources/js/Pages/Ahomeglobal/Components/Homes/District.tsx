const District = () => {
	return (
		<div className="grid grid-cols-12 w-full grid-rows-4 gap-2 xs:gap-4">
			<div className="col-span-8 row-span-1 ">
				<div className='relative h-full w-full overflow-hidden rounded-md group'>
					<div
						className="bg-[url('https://cdn1.ivivu.com/images/2025/04/16/13/phuquoc_show_ebkmxx_.webp')] 
										relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110">
						<a row-spans-2 className="des__link" href="/khach-san-phu-quoc" target="_blank">
							<h4 row-spans-2>Phú Quốc</h4><span row-spans-2
								className="only-show-desktop">921 khách sạn</span>
						</a>
					</div>
				</div>
			</div>
			<div className="col-span-4 row-span-2">
				<div className='relative h-full w-full overflow-hidden rounded-md group'>
					<div
						className="bg-[url('https://cdn1.ivivu.com/images/2025/12/26/17/vungtau_show_xpg1kr_.webp')] 
										relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 ">
						<a
							row-spans-2 className="absolute left-2 md:left-10 bottom-2 md:bottom-10" href="/khach-san-vung-tau" target="_blank">
							<h4 row-spans-2>Vũng Tàu</h4><span row-spans-2
								className="only-show-desktop hidden md:visible">706 khách sạn</span>
						</a>
					</div>
				</div>
			</div>
			<div className="col-span-4 row-span-1">
				<div className='relative h-full w-full overflow-hidden rounded-md group'>
					<div
						className="bg-[url('https://cdn1.ivivu.com/images/2025/04/16/10/dalat_show_2ancd3_.webp')] w-full h-full bg-cover bg-center"><a
							row-spans-2 className="des__link" href="/khach-san-da-lat" target="_blank">
							<h4 row-spans-2>Đà Lạt</h4><span row-spans-2
								className="only-show-desktop">1181 khách sạn</span>
						</a>
					</div>
				</div>
			</div>
			<div className="col-span-4 row-span-1">
				<div className='relative h-full w-full overflow-hidden rounded-md group'>
					<div
						className="bg-[url('https://cdn1.ivivu.com/images/2025/04/18/11/quynhon_show_bymdwj_.webp')] w-full h-full bg-cover bg-center"><a
							row-spans-2 className="des__link" href="/khach-san-quy-nhon" target="_blank">
							<h4 row-spans-2>Quy Nhơn</h4><span row-spans-2
								className="only-show-desktop">334 khách sạn</span>
						</a>
					</div>
				</div>
			</div>
			<div className="col-span-4 row-span-2">
				<div
					className="bg-[url('https://cdn1.ivivu.com/images/2025/04/16/11/nhatrang_show_su3sb3_.webp')] w-full h-full bg-cover bg-center">
					<a row-spans-2 className="des__link" href="/khach-san-nha-trang" target="_blank">
						<h4 row-spans-2>Nha Trang</h4><span row-spans-2
							className="only-show-desktop">1023 khách sạn</span>
					</a></div>
			</div>
			<div className="col-span-8 row-span-1">
				<div
					className="bg-[url('https://cdn1.ivivu.com/images/2025/04/16/11/danang_show_2lamnx_.webp')] w-full h-[124px] md:h-[200px] bg-cover bg-center"><a
						row-spans-2 className="des__link" href="/khach-san-da-nang" target="_blank">
						<h4 row-spans-2>Đà Nẵng</h4><span row-spans-2
							className="only-show-desktop">1364 khách sạn</span>
					</a></div>
			</div>
			<div className="col-span-4 row-span-1">
				<div
					className="bg-[url('https://cdn1.ivivu.com/images/2025/04/16/11/phanthiet_show_wrdctj_.webp')] w-full h-full bg-cover bg-center">
					<a row-spans-2 className="des__link" href="/khach-san-phan-thiet" target="_blank">
						<h4 row-spans-2>Phan Thiết</h4><span row-spans-2
							className="only-show-desktop">496 khách sạn</span>
					</a></div>
			</div>

			<div className="col-span-4 row-span-1">
				<div
					className="bg-[url('https://cdn1.ivivu.com/images/2025/04/16/11/phuyen_show_vtndp3_.webp')] w-full h-full bg-cover bg-center"><a
						row-spans-2 className="des__link" href="/khach-san-tinh-phu-yen" target="_blank">
						<h4 row-spans-2>Phú Yên</h4><span row-spans-2
							className="only-show-desktop">18 khách sạn</span>
					</a></div>
			</div>
		</div>
	)
}

export default District;