<div class="fi-wi-widget rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Most Viewed Pages</h3>
            <div class="text-sm text-gray-500 dark:text-gray-400">Last 30 days</div>
        </div>
        
        @if(empty($topPages))
            <div class="mt-4 text-center text-gray-500 dark:text-gray-400 py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <div class="text-sm font-medium mt-2 text-gray-900 dark:text-gray-100">No page tracking data available</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">Real Google Analytics API integration required to track most viewed pages</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">Configure Google Analytics in Settings to enable page tracking</div>
            </div>
        @else
            <div class="mt-4">
                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Page</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Page Views</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unique Views</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg. Time</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bounce Rate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($topPages as $index => $page)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-6 w-6 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ $index + 1 }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $page['title'] ?? 'Unknown Page' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $page['page'] ?? '/' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($page['views'] ?? 0) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($page['unique_views'] ?? 0) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $page['avg_time_on_page'] ?? '0:00' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                                {{ ($page['bounce_rate'] ?? 0) > 70 ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                                                   (($page['bounce_rate'] ?? 0) > 50 ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                                {{ number_format($page['bounce_rate'] ?? 0, 1) }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(count($topPages) > 0)
                    <div class="mt-4 flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                        <div>Showing {{ count($topPages) }} most viewed pages</div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-100 dark:bg-green-900 rounded-full mr-1"></div>
                                <span>Good (&lt;50%)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-100 dark:bg-yellow-900 rounded-full mr-1"></div>
                                <span>Fair (50-70%)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-100 dark:bg-red-900 rounded-full mr-1"></div>
                                <span>High (&gt;70%)</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>