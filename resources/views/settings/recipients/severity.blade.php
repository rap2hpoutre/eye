<p class="text-2xl text-grey-darker font-medium mb-4 mt-8">3. Configure severity levels:</p>
<p class="text-grey max-w-md mx-auto mb-6">Select which severity level(s) you want this receipient to be notified of.</p>
<div class="flex flex-col md:flex-row justify-center">
    <div>
        <div class="pretty p-switch p-fill mb-4">
            <input type="checkbox" {{ old('low', '1') === '1' ? 'checked="checked"' : '' }} value="1" name="low"/>
            <div class="state p-primary">
                <label class="text-grey-darker">Low</label>
            </div>
        </div>
    </div>
    <div>
        <div class="pretty p-switch p-fill mb-4">
            <input type="checkbox" {{ old('medium', '1') === '1' ? 'checked="checked"' : '' }} value="1" name="medium"/>
            <div class="state p-primary">
                <label class="text-grey-darker">Medium</label>
            </div>
        </div>
    </div>
    <div>
        <div class="pretty p-switch p-fill mb-4">
            <input type="checkbox" {{ old('high', '1') === '1' ? 'checked="checked"' : '' }} value="1" name="high"/>
            <div class="state p-primary">
                <label class="text-grey-darker">High</label>
            </div>
        </div>
    </div>
</div>
