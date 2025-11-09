<TopPage></TopPage>
                        <div className='grid grid-cols-3 gap-1'>
                            <div className='col-span-3 md:col-span-2'>
                                <TopComment></TopComment>
                            </div>
                            <div className='col-span-0 md:col-span-1 bg-white justify-center flex p-1 rounded-md'>
                                <span className='text-black font-bold text-xl'>Quảng cáo!</span>
                            </div>
                        </div>
                        <HomeDemo></HomeDemo>
                        <Banner page={banner}></Banner>
                        <PageInfo laravelVersion={laravelVersion} phpVersion={phpVersion}></PageInfo>
                        <RandomHorizon></RandomHorizon>
                        <GoldChart></GoldChart>
                        <SuggetVertical pageId={banner.id} title={'Giới thiệu'}></SuggetVertical>
                        <CenterCategory></CenterCategory>
                        {/* Dùng WhenVisible thì data sẽ được gọi khi đối tượng được hiển thị  */}
                        <SwipeToSlide></SwipeToSlide>
                        <VietLotChart></VietLotChart>
                        <WhenVisible data={['timeLine']} fallback={() => <ListSke></ListSke>}>
                            <TimeList items={timeLine}></TimeList>
                        </WhenVisible>
                        <TestDef></TestDef>
                        <HomeVideos></HomeVideos>