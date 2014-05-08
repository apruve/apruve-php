watch("src/Apruve/(.*).php") do |match|
  run_test %{tests/Apruve/#{match[1]}Test.php}
end

watch("tests/Apruve/.*Test.php") do |match|
  run_test match[0]
end

def run_test(file)
  clear_console
  unless File.exist?(file)
    puts "#{file} does not exist"
    return
  end
     
  puts "Running #{file}"
  result = `vendor/bin/phpunit #{file}`
  puts result

  if result.match /OK/
    notify file, "Tests passed successfully", 2000
  elsif result.match /FAILURES\!/
    notify_failed file, result
  end
end


def notify(title, msg, show_time)
  # system "notify-send '#{title}' '#{msg}' -t #{show_time}"
end

def notify_failed(cmd, result)
  failed_examples = result.scan(/failure:\n\n(.*)\n/)
  notify cmd, failed_examples[0], 5000
end

def clear_console
  puts "\e[H\e[2J"
end
