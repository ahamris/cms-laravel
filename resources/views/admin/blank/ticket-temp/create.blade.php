<x-layouts.admin title="Create New Ticket">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Ticket</h1>
                <p class="text-gray-600">Create a new support ticket</p>
            </div>
            <a href="#" class="text-primary hover:text-primary/80">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Tickets
            </a>
        </div>

        {{-- Error Messages --}}
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg hidden">
            <i class="fa-solid fa-exclamation-circle mr-2"></i>
            <ul class="list-disc list-inside">
                <li>Example validation error goes here</li>
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="#" method="POST">
                <div class="p-6 space-y-6">
                    {{-- Ticket Information --}}
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Ticket Information</h2>
                        <p class="mt-1 text-sm text-gray-500">Enter the ticket details.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Title --}}
                        <div class="md:col-span-2">
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary text-sm"
                                    placeholder="Enter a descriptive title"
                                    value="Login button not working">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary text-sm"
                                    placeholder="Provide detailed information about the issue">User reports that the login button does not respond when clicked on mobile devices.</textarea>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary text-sm">
                                    <option selected>Open</option>
                                    <option>In Progress</option>
                                    <option>On Hold</option>
                                    <option>Resolved</option>
                                    <option>Closed</option>
                                </select>
                            </div>
                        </div>

                        {{-- Priority --}}
                        <div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <select name="priority" id="priority"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary text-sm">
                                    <option>Low</option>
                                    <option>Medium</option>
                                    <option selected>High</option>
                                    <option>Urgent</option>
                                </select>
                            </div>
                        </div>

                        {{-- Ticket Type (Fake Data) --}}
                        <div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label for="ticket_type_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Ticket Type
                                </label>
                                <select name="ticket_type_id" id="ticket_type_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary text-sm">
                                    <option>Bug</option>
                                    <option selected>Support</option>
                                    <option>Feature Request</option>
                                </select>
                            </div>
                        </div>

                        {{-- Assignee (Fake Users) --}}
                        <div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                                <select name="assigned_to" id="assigned_to"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary text-sm">
                                    <option>Unassigned</option>
                                    <option selected>John Doe</option>
                                    <option>Jane Smith</option>
                                    <option>Michael Brown</option>
                                </select>
                            </div>
                        </div>

                        {{-- Reporter (Fake Users) --}}
                        <div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Reporter</label>
                                <select name="user_id" id="user_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary text-sm">
                                    <option selected>Alice Johnson</option>
                                    <option>Robert Lee</option>
                                    <option>Emily Davis</option>
                                </select>
                            </div>
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                <input type="datetime-local" name="due_date" id="due_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary text-sm"
                                    value="2025-10-01T18:00">
                            </div>
                        </div>

                        {{-- Additional Information --}}
                        <div class="md:col-span-2 mt-6">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Additional Information</label>
                                    <button type="button" onclick="addAdditionalField()"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-primary hover:bg-primary/90">
                                        <i class="fa-solid fa-plus mr-1.5"></i> Add Item
                                    </button>
                                </div>
                                <div id="additionalFields" class="space-y-4">
                                    <!-- Example fake field -->
                                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Key</label>
                                                <input type="text" name="additional_keys[]" value="Browser"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                                <input type="text" name="additional_values[]" value="Chrome 117"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <a href="#"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg text-white bg-primary hover:bg-primary/90">
                        Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
